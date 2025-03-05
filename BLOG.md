
# How to Create a Modular REST API with Mezzio PHP Framework


## Directory Structure

```sh
├── mezzio
├── bin
├── data
├── public
├── src
│   ├── Accounts
│   │   ├── config
│   │   │   │── routes.php
│   │   ├── src
│   │   │   │── Handler
│   │   │   │   ├── Users
│   │   │   │   │ 	├── CreateHandler.php
│   │   │   │── Middleware
│   │   │   │   │ 	├── 
│   │   │   │   ├── ConfigProvider.php
│   ├── App
│   │   ├── src
│   │   │   │── Handler
│   │   │   │   ├── HomePageHandler.php
│   │   │   │   ├── HomePageHandlerFactory.php
│   │   │   │── Middleware
│   │   │   │   │ 	├── ErrorResponseGenerator.php
│   │   │   │   │ 	├── NotFoundResponseGenerator.php
│   ├── test
│   ├── vendor
│   ├── composer.json
```

config/modules.config.php

```php
<?php

return [
    'Users',
];
```

index.php

```php
if (! is_file('config/module.config.php')) {
    throw new RuntimeException('Module configuration is missing or incorrect.');
}
```

index.php

```php
(function () {
    /** @var \Psr\Container\ContainerInterface $container */
    $container = require 'config/container.php';

    /** @var \Mezzio\Application $app */
    $app = $container->get(\Mezzio\Application::class);
    $factory = $container->get(\Mezzio\MiddlewareFactory::class);

    // Execute programmatic/declarative middleware pipeline and routing
    // configuration statements
    (require 'config/pipeline.php')($app, $factory, $container);
    (require 'config/routes.php')($app, $factory, $container);

    // Register module routes ..
    $modules = require 'config/module.config.php';
    $moduleProviders = [];
    foreach ($modules as $module) {
        $configProviderClass = $module . '\ConfigProvider';
        if (class_exists($configProviderClass) && method_exists($configProviderClass, 'registerRoutes')) {
            $configProviderClass::registerRoutes($app, $container);
        }
    }

    $app->run();
})();
```

bin/module.php


```php
<?php

declare(strict_types=1);

// Command line options
$options = getopt('', ['register:', 'deregister:']);

// Path to the modules configuration file
$modulesConfigFile = __DIR__ . '/../config/modules.config.php';

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
```

