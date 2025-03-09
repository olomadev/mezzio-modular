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
    // Roles (private)
    $app->route('/api/authorization/roles/create', [...$auth, ...[Authorization\Handler\Roles\CreateHandler::class]], ['POST']);
    $app->route('/api/authorization/roles/update/:roleId', [...$auth, ...[Authorization\Handler\Roles\UpdateHandler::class]], ['PUT']);
    $app->route('/api/authorization/roles/delete/:roleId', [...$auth, ...[Authorization\Handler\Roles\DeleteHandler::class]], ['DELETE']);
    $app->route('/api/authorization/roles/findAll', [Authorization\Handler\Roles\FindAllHandler::class], ['GET']);
    $app->route('/api/authorization/roles/findAllByPaging', [...$auth, ...[Authorization\Handler\Roles\FindAllByPagingHandler::class]], ['GET']);
    $app->route('/api/authorization/roles/findOneById/:roleId', [...$auth, ...[Authorization\Handler\Roles\FindOneByIdHandler::class]], ['GET']);

    // Permissions (private)
    $app->route('/api/authorization/permissions/create', [...$auth, [Authorization\Handler\Permissions\CreateHandler::class]], ['POST']);
    $app->route('/api/authorization/permissions/copy/:permId', [...$auth, [Authorization\Handler\Permissions\CopyHandler::class]], ['POST']);
    $app->route('/api/authorization/permissions/update/:permId', [...$auth, [Authorization\Handler\Permissions\UpdateHandler::class]], ['PUT']);
    $app->route('/api/authorization/permissions/delete/:permId', [...$auth, [Authorization\Handler\Permissions\DeleteHandler::class]], ['DELETE']);
    $app->route('/api/authorization/permissions/findAll', [JwtAuthenticationMiddleware::class, Authorization\Handler\Permissions\FindAllHandler::class], ['GET']);
    $app->route('/api/authorization/permissions/findAllByPaging', [...$auth, [Authorization\Handler\Permissions\FindAllByPagingHandler::class]], ['GET']);

    // common options (public)
    $app->route('/api/authorization/actions/findAll', Authorization\Handler\Actions\FindAllHandler::class, ['GET']);
    $app->route('/api/authorization/methods/findAll', Authorization\Handler\Methods\FindAllHandler::class, ['GET']);

};
