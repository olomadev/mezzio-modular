<?php

declare(strict_types=1);

namespace Authorization;

use Mezzio\Application;
use Psr\Container\ContainerInterface;
use Laminas\Cache\Storage\StorageInterface;
use Olobase\Mezzio\ColumnFiltersInterface;
use Olobase\Mezzio\Authorization\PermissionModelInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;

/**
 * The configuration provider for the Authorization module
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
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'input_filters' => $this->getInputFilters(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'invokables' => [
                
            ],
            'aliases' => [
                Model\PermissionModel::class => PermissionModelInterface::class, // permission model used by Authorization
            ],
            'factories'  => [

                // handlers - roles
                Handler\Roles\CreateHandler::class => Handler\Roles\CreateHandlerFactory::class,
                Handler\Roles\UpdateHandler::class => Handler\Roles\UpdateHandlerFactory::class,
                Handler\Roles\DeleteHandler::class => Handler\Roles\DeleteHandlerFactory::class,
                Handler\Roles\FindOneByIdHandler::class => Handler\Roles\FindOneByIdHandlerFactory::class,
                Handler\Roles\FindAllHandler::class => Handler\Roles\FindAllHandlerFactory::class,
                Handler\Roles\FindAllByPagingHandler::class => Handler\Roles\FindAllByPagingHandlerFactory::class,

                // handlers - permissions
                Handler\Permissions\CopyHandler::class => Handler\Permissions\CopyHandlerFactory::class,
                Handler\Permissions\CreateHandler::class => Handler\Permissions\CreateHandlerFactory::class,
                Handler\Permissions\UpdateHandler::class => Handler\Permissions\UpdateHandlerFactory::class,
                Handler\Permissions\DeleteHandler::class => Handler\Permissions\DeleteHandlerFactory::class,
                Handler\Permissions\FindAllHandler::class => Handler\Permissions\FindAllHandlerFactory::class,
                Handler\Permissions\FindAllByPagingHandler::class => Handler\Permissions\FindAllByPagingHandlerFactory::class,

                // models
                Model\RoleModelInterface::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $cacheStorage = $container->get(StorageInterface::class);
                    $columnFilters = $container->get(ColumnFiltersInterface::class);
                    $roles = new TableGateway('roles', $dbAdapter, null, new ResultSet(ResultSet::TYPE_ARRAY));
                    $rolePermissions = new TableGateway('rolePermissions', $dbAdapter, null, new ResultSet(ResultSet::TYPE_ARRAY));
                    return new Model\RoleModel(
                        $roles,
                        $rolePermissions,
                        $cacheStorage,
                        $columnFilters
                    );
                },
                PermissionModelInterface::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $cacheStorage = $container->get(StorageInterface::class);
                    $columnFilters = $container->get(ColumnFiltersInterface::class);
                    $permissions = new TableGateway('permissions', $dbAdapter, null, new ResultSet(ResultSet::TYPE_ARRAY));
                    return new Model\PermissionModel(
                        $permissions, 
                        $cacheStorage,
                        $columnFilters
                    );
                },
            ],
        ];
    }
    
    /**
     * Returns the input filter dependencies
     */
    public function getInputFilters() : array
    {
        return [
            'factories' => [
                // Permissions
                // InputFilter\Permissions\SaveFilter::class => InputFilter\Permissions\SaveFilterFactory::class,
                // InputFilter\Permissions\DeleteFilter::class => InputFilter\Permissions\DeleteFilterFactory::class,
                // Roles
                InputFilter\Roles\SaveFilter::class => InputFilter\Roles\SaveFilterFactory::class,
                InputFilter\Roles\DeleteFilter::class => InputFilter\Roles\DeleteFilterFactory::class,
            ]
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
