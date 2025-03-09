<?php

declare(strict_types=1);

namespace Categories;

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
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'factories'  => [

                // categories
                Handler\CreateHandler::class => Handler\CreateHandlerFactory::class,
                Handler\UpdateHandler::class => Handler\UpdateHandlerFactory::class,
                Handler\DeleteHandler::class => Handler\DeleteHandlerFactory::class,
                Handler\FindAllHandler::class => Handler\FindAllHandlerFactory::class,
                Handler\FindAllByPagingHandler::class => Handler\FindAllByPagingHandlerFactory::class,

                // models
                Model\CategoryModelInterface::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $categories = new TableGateway('categories', $dbAdapter, null, new ResultSet(ResultSet::TYPE_ARRAY));
                    $cacheStorage = $container->get(StorageInterface::class);
                    return new Model\CategoryModel($categories, $cacheStorage);
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
