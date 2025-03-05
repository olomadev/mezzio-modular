<?php

declare(strict_types=1);

// Command line options
$options = getopt('', ['register:', 'deregister:']);

// Path to the modules configuration file
$modulesConfigFile = __DIR__ . '/../config/module.config.php';

// Read the existing modules
if (file_exists($modulesConfigFile)) {
    $modulesConfig = include $modulesConfigFile;
} else {
    $modulesConfig = [];
}

// Register module
if (isset($options['register'])) {
    $moduleName = $options['register'];
    if (!in_array($moduleName, $modulesConfig)) {
        $modulesConfig[] = $moduleName;
        echo "\033[32mRegistering module: $moduleName\n\033[0m"; // Success message

        // Write the updated modules list to the config file
        file_put_contents($modulesConfigFile, "<?php\nreturn [\n    " . implode(",\n    ", array_map(fn($m) => "'$m'", $modulesConfig)) . "\n];\n");

        // Run the composer register command
        exec("composer mezzio mezzio:module:register $moduleName");

        echo "\033[32mAutoload updated successfully.\n\033[0m"; // Success message
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

        // Run the composer deregister command
        exec("composer mezzio mezzio:module:deregister $moduleName");

        echo "\033[32mAutoload updated successfully.\n\033[0m"; // Success message
    } else {
        echo "\033[33mModule '{$moduleName}' is not found in modules.config.php.\n\033[0m"; // Error message
    }
}
