<?php

declare(strict_types=1);

use Mezzio\Application;
use Psr\Container\ContainerInterface;
use Authentication\Middleware\JwtAuthenticationMiddleware;

return function (Application $app, ContainerInterface $container) {

    $auth = [
        JwtAuthenticationMiddleware::class,
        Mezzio\Authorization\AuthorizationMiddleware::class,
    ];
    // Common
    $app->route('/api/common/files/findOneById/:fileId', Common\Handler\Files\FindOneByIdHandler::class, ['GET']);
    $app->route('/api/common/files/readOneById/:fileId', Common\Handler\Files\ReadOneByIdHandler::class, ['GET']);
};
