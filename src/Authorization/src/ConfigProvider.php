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
            'translator' => $this->getTranslations(),
        ];
    }

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

                // handlers - user roles
                Handler\UserRoles\AssignHandler::class => Handler\UserRoles\AssignHandlerFactory::class,
                Handler\UserRoles\UnassignHandler::class => Handler\UserRoles\UnassignHandlerFactory::class,
                Handler\UserRoles\FindAllByPagingHandler::class => Handler\UserRoles\FindAllByPagingHandlerFactory::class,

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
                    $userRoles = new TableGateway('userRoles', $dbAdapter, null, new ResultSet(ResultSet::TYPE_ARRAY));
                    return new Model\RoleModel(
                        $roles,
                        $rolePermissions,
                        $userRoles,
                        $cacheStorage,
                        $columnFilters
                    );
                },
                Model\UserRoleModelInterface::Class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $columnFilters = $container->get(ColumnFiltersInterface::class);
                    $userRoles = new TableGateway('userRoles', $dbAdapter, null, new ResultSet(ResultSet::TYPE_ARRAY));
                    return new Model\UserRoleModel($userRoles, $columnFilters);
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

    public function getInputFilters() : array
    {
        return [
            'factories' => [
                // Permissions
                InputFilter\Permissions\SaveFilter::class => InputFilter\Permissions\SaveFilterFactory::class,
                InputFilter\Permissions\DeleteFilter::class => InputFilter\Permissions\DeleteFilterFactory::class,
                // Roles
                InputFilter\Roles\SaveFilter::class => InputFilter\Roles\SaveFilterFactory::class,
                InputFilter\Roles\DeleteFilter::class => InputFilter\Roles\DeleteFilterFactory::class,
                // UserRoles
                InputFilter\UserRoles\AssignRoleFilter::class => InputFilter\UserRoles\AssignRoleFilterFactory::class,
                InputFilter\UserRoles\UnassignRoleFilter::class => InputFilter\UserRoles\UnassignRoleFilterFactory::class,
            ]
        ];
    }

    public function getTranslations()
    {
        return [
            'translation_file_patterns' => [
                [
                    'type' => 'PhpArray',
                    'base_dir' => __DIR__ . '/../i18n',
                    'pattern' => '%s/messages.php',
                ]
            ],
        ];
    }

    public static function registerRoutes(Application $app, ContainerInterface $container): void
    {
        (require __DIR__ . '/../config/routes.php')($app, $container);
    }

}
