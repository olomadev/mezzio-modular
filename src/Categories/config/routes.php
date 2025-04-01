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
    // Categories
    $app->route('/api/categories/findAll', [...$auth, ...[Categories\Handler\FindAllHandler::class]], ['GET']);
    $app->route('/api/categories/findAllByPaging', [...$auth, ...[Categories\Handler\FindAllByPagingHandler::class]], ['GET']);
    $app->route('/api/categories/create', [...$auth, ...[Categories\Handler\CreateHandler::class]], ['POST']);
    $app->route('/api/categories/delete/:categoryId', [...$auth, ...[Categories\Handler\DeleteHandler::class]], ['DELETE']);
    $app->route('/api/categories/update/:categoryId', [...$auth, ...[Categories\Handler\UpdateHandler::class]], ['PUT']);
};
