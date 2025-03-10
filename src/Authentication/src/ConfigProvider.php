<?php

declare(strict_types=1);

namespace Authentication;

use Mezzio\Application;
use Mezzio\Authentication\AuthenticationInterface;
use Psr\Container\ContainerInterface;
use Authentication\EventListener\LoginListener;
use Authentication\EventListener\LoginListenerFactory;
use Laminas\Cache\Storage\StorageInterface;
use Olobase\Mezzio\ColumnFiltersInterface;
use Olobase\Mezzio\Authentication\JwtEncoderInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;

/**
 * The configuration provider for the Authentication module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'authentication' => [
                'tablename' => 'users',
                'username' => 'email',
                'password' => 'password',
                'form' => [
                    'username' => 'username',
                    'password' => 'password',
                ]
            ],
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'aliases' => [
                AuthenticationInterface::class => Authentication\JwtAuthentication::class,
            ],
            'factories' => [
                // classes
                Authentication\JwtAuthentication::class => Authentication\JwtAuthenticationFactory::class,

                // middlewares
                Middleware\JwtAuthenticationMiddleware::class => Middleware\JwtAuthenticationMiddlewareFactory::class,

                // listeners
                LoginListener::class => LoginListenerFactory::class,

                // helpers
                Helper\TokenEncryptHelper::class => Helper\TokenEncryptHelperFactory::class,

                // handlers
                Handler\TokenHandler::class => Handler\TokenHandlerFactory::class,
                Handler\RefreshHandler::class => Handler\RefreshHandlerFactory::class,
                Handler\LogoutHandler::class => Handler\LogoutHandlerFactory::class,
                Handler\SessionUpdateHandler::class => Handler\SessionUpdateHandlerFactory::class,

                // handlers - failed logins
                Handler\FailedLogins\DeleteHandler::class => Handler\FailedLogins\DeleteHandlerFactory::class,
                Handler\FailedLogins\FindAllByPagingHandler::class => Handler\FailedLogins\FindAllByPagingHandlerFactory::class,
                Handler\FailedLogins\FindAllIpAdressesHandler::class => Handler\FailedLogins\FindAllIpAdressesHandlerFactory::class,
                Handler\FailedLogins\FindAllUsernamesHandler::class => Handler\FailedLogins\FindAllUsernamesHandlerFactory::class,
                

                // models
                Model\TokenModelInterface::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $tokenEncrypt = $container->get(Helper\TokenEncryptHelper::class);
                    $cacheStorage = $container->get(StorageInterface::class);
                    $jwtEncoder = $container->get(JwtEncoderInterface::class);
                    $users = new TableGateway('users', $dbAdapter, null);
                    return new Model\TokenModel($container->get('config'), $cacheStorage, $tokenEncrypt, $jwtEncoder, $users);
                },
                Model\FailedLoginModelInterface::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $simpleCache = $container->get(SimpleCacheInterface::class);
                    $columnFilters = $container->get(ColumnFiltersInterface::class);
                    $users = new TableGateway('users', $dbAdapter, null, new ResultSet(ResultSet::TYPE_ARRAY));
                    $failedLogins = new TableGateway('failedLogins', $dbAdapter, null, new ResultSet(ResultSet::TYPE_ARRAY));
                    return new Model\FailedLoginModel($users, $failedLogins, $simpleCache, $columnFilters);
                },
            ],
        ];
    }

    /**
     * Registers routes for the module
     */
    public static function registerRoutes(Application $app, ContainerInterface $container): void
    {
        (require __DIR__ . '/../config/routes.php')($app, $container);
    }
}
