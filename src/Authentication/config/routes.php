<?php

declare(strict_types=1);

use Mezzio\Application;
use Psr\Container\ContainerInterface;
use Authentication\Handler\SessionUpdateHandler;
use Authentication\Middleware\JwtAuthenticationMiddleware;

return function (Application $app, ContainerInterface $container) {

    // Auth (public)
    $app->route('/api/auth/token', Authentication\Handler\TokenHandler::class, ['POST']);
    $app->route('/api/auth/refresh', [Authentication\Handler\RefreshHandler::class], ['POST']);
    $app->route('/api/auth/logout', [Authentication\Handler\LogoutHandler::class], ['GET']);
    $app->route('/api/auth/session', [JwtAuthenticationMiddleware::class, SessionUpdateHandler::class], ['POST']);

    $auth = [
        JwtAuthenticationMiddleware::class,
        Mezzio\Authorization\AuthorizationMiddleware::class,
    ];
    // FailedLogins (private)
    $app->route('/api/authentication/failedLogins/delete/:loginId', [...$auth, [Authentication\Handler\FailedLogins\DeleteHandler::class]], ['DELETE']);
    $app->route('/api/authentication/failedLogins/findAllByPaging', [...$auth, [Authentication\Handler\FailedLogins\FindAllByPagingHandler::class]], ['GET']);
    $app->route('/api/authentication/failedLoginIps/findAll', [...$auth, [Authentication\Handler\FailedLogins\FindAllIpAdressesHandler::class]], ['GET']);
    $app->route('/api/authentication/failedLoginUsernames/findAll', [...$auth, [Authentication\Handler\FailedLogins\FindAllUsernamesHandler::class]], ['GET']);
};
