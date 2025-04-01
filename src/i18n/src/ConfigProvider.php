<?php

declare(strict_types=1);

namespace i18n;

use Mezzio\Application;
use Psr\Container\ContainerInterface;
use Laminas\Cache\Storage\StorageInterface;
use Olobase\Mezzio\ColumnFiltersInterface;
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
            'translator' => [
                'languages' => [
                    'en', // default locale
                    'en'  // fallback locale
                ],
            ],
        ];
    }

    public function getDependencies() : array
    {
        return [
            'factories'  => [

                // modules
                Handler\FindAllHandler::class => Handler\FindAllHandlerFactory::class,
                Handler\FindAllByPagingHandler::class => Handler\FindAllByPagingHandlerFactory::class,

                // models
                Model\i18nModelInterface::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $i18nSettings = new TableGateway('i18nSettings', $dbAdapter, null, new ResultSet(ResultSet::TYPE_ARRAY));
                    $cacheStorage = $container->get(StorageInterface::class);
                    $columnFilters = $container->get(ColumnFiltersInterface::class);
                    return new Model\i18nModel($i18nSettings, $cacheStorage, $columnFilters);
                },
            ],
        ];
    }
    
    public static function registerRoutes(Application $app, ContainerInterface $container): void
    {
        (require __DIR__ . '/../config/routes.php')($app, $container);
    }

}
