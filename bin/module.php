<?php

declare(strict_types=1);

// Command line options
$options = getopt('', ['register:', 'deregister:']);

// Path to the modules configuration file
$modulesConfigFile = __DIR__ . '/../config/module.config.php';
$composerJsonFile = __DIR__ . '/../composer.json';
$composerLockFile = __DIR__ . '/../composer.lock'; // Path to composer.lock file (project root)

// Read the existing modules
if (file_exists($modulesConfigFile)) {
    $modulesConfig = include $modulesConfigFile;
} else {
    $modulesConfig = [];
}

// Read the composer.json
$composerJson = json_decode(file_get_contents($composerJsonFile), true);
$repositories = $composerJson['repositories'] ?? [];

// Function to print the composer install output in green using passthru
function runComposerInstall()
{
    echo "\033[32mRunning composer install...\033[0m\n";
    passthru("composer install", $returnVar);
    if ($returnVar === 0) {
        echo "\033[32mComposer install completed successfully.\033[0m\n";
    } else {
        echo "\033[31mComposer install failed with status code $returnVar.\033[0m\n";
    }
}

// Function to get the module's name from its composer.json
function getModuleNameFromComposerJson(string $modulePath): ?string
{
    $composerFile = $modulePath . '/composer.json';
    if (file_exists($composerFile)) {
        $composerData = json_decode(file_get_contents($composerFile), true);
        return $composerData['name'] ?? null;
    }
    return null;
}

// Register module
if (isset($options['register'])) {
    $moduleName = $options['register'];
    if (!in_array($moduleName, $modulesConfig)) {
        $modulesConfig[] = $moduleName;
        echo "\033[32mRegistering module: $moduleName\n\033[0m"; // Success message

        // Add to repositories in composer.json
        $repositories[] = [
            'type' => 'path',
            'url' => "./src/$moduleName",
            'options' => ['symlink' => true],
        ];

        // Write the updated modules list to the config file
        file_put_contents($modulesConfigFile, "<?php\nreturn [\n    " . implode(",\n    ", array_map(fn($m) => "'$m'", $modulesConfig)) . "\n];\n");

        // Update composer.json with new repositories
        $composerJson['repositories'] = $repositories;
        file_put_contents($composerJsonFile, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // Run the composer register command
        exec("composer mezzio mezzio:module:register $moduleName");

        echo "\033[32mAutoload updated successfully.\n\033[0m"; // Success message

        // Before running composer install, remove composer.lock
        if (file_exists($composerLockFile)) {
            unlink($composerLockFile);
            echo "\033[32mcomposer.lock file removed successfully.\n\033[0m";
        }

        /**
         * $modulePath = __DIR__ . '/../src/' . $moduleName;
         * $moduleFullName = getModuleNameFromComposerJson($modulePath);
         * 
         * Bu kısımda composer require "moduleFullName" ile paket install yapılacak...
         * 
         */

        // Run composer install and print output
        runComposerInstall();
    } else {
        echo "\033[33mModule '{$moduleName}' is already enabled in modules.config.php.\n\033[0m"; // Info message
    }
}

// Deregister module
if (isset($options['deregister'])) {
    $moduleName = $options['deregister'];
    if (($key = array_search($moduleName, $modulesConfig)) !== false) {
        unset($modulesConfig[$key]);
        echo "\033[32mDeregistering module: $moduleName\n\033[0m"; // Success message

        // Write the updated modules list to the config file
        file_put_contents($modulesConfigFile, "<?php\nreturn [\n    " . implode(",\n    ", array_map(fn($m) => "'$m'", $modulesConfig)) . "\n];\n");

        // Remove from repositories in composer.json
        $repositories = array_filter($repositories, fn($repo) => !isset($repo['url']) || strpos($repo['url'], $moduleName) === false);
        $composerJson['repositories'] = array_values($repositories);
        file_put_contents($composerJsonFile, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // Get the full name of the module from its composer.json (src/i18n/$moduleName/composer.json)
        $modulePath = __DIR__ . '/../src/' . $moduleName;
        $moduleFullName = getModuleNameFromComposerJson($modulePath);

        if ($moduleFullName) {
            // Run composer remove with the module's full name
            exec("composer remove $moduleFullName", $output, $returnVar);

            if ($returnVar === 0) {
                echo "\033[32mModule '{$moduleFullName}' removed successfully from composer.json.\n\033[0m"; // Success message
            } else {
                echo "\033[31mFailed to remove module '{$moduleFullName}' from composer.json.\n\033[0m"; // Error message
            }
        } else {
            echo "\033[31mFailed to get the full name of module '$moduleName'.\n\033[0m"; // Error message
        }

        // Run the composer deregister command
        exec("composer mezzio mezzio:module:deregister $moduleName");

        echo "\033[32mAutoload updated successfully.\n\033[0m"; // Success message

        // Before running composer install, remove composer.lock
        if (file_exists($composerLockFile)) {
            unlink($composerLockFile);
            echo "\033[32mcomposer.lock file removed successfully.\n\033[0m";
        }

        // Run composer install and print output
        runComposerInstall();
    } else {
        echo "\033[33mModule '{$moduleName}' is not found in modules.config.php.\n\033[0m"; // Error message
    }
}
