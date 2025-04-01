<?php

declare(strict_types=1);

use Mezzio\Application;
use Psr\Container\ContainerInterface;

return function (Application $app, ContainerInterface $container) {
    // Common
    $app->route('/api/i18n/languages/findAll', i18n\Handler\FindAllHandler::class, ['GET']);
};