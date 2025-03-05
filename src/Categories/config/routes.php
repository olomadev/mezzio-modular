<?php

declare(strict_types=1);

use Mezzio\Authenticationlication;
use Psr\Container\ContainerInterface;
use Authentication\Handler\SessionUpdateHandler;
use Authentication\Middleware\JwtAuthenticationMiddleware;

return function (Authenticationlication $app, ContainerInterface $container) {

    $auth = [
        JwtAuthenticationMiddleware::class,
        Mezzio\Authorization\AuthorizationMiddleware::class,
    ];
    // Categories
    $app->route('/api/categories/list/findAll', [...$auth, ...[Categories\Handler\FindAllHandler::class]], ['GET']);
    $app->route('/api/categories/list/findAllByPaging', [...$auth, ...[Categories\Handler\FindAllByPagingHandler::class]], ['GET']);
    $app->route('/api/categories/list/create', [...$auth, ...[Categories\Handler\CreateHandler::class]], ['POST']);
    $app->route('/api/categories/list/delete/:categoryId', [...$auth, ...[Categories\Handler\DeleteHandler::class]], ['DELETE']);
    $app->route('/api/categories/list/update/:categoryId', [...$auth, ...[Categories\Handler\UpdateHandler::class]], ['PUT']);
};
