<?php

declare(strict_types=1);

namespace Common;

use Mezzio\Application;
use Psr\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Cache\Storage\StorageInterface;
use Predis\ClientInterface as PredisInterface;
use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;
use Laminas\EventManager\EventManagerInterface;
use Laminas\I18n\Translator\TranslatorInterface;
// use Laminas\ServiceManager\Factory\InvokableFactory;

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
            'data_manager' => [
                'common_schema_module' => 'Common',
            ],
            'dependencies' => $this->getDependencies(),
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
            'delegators' => [
                TranslatorInterface::class => [
                    'Common\Factory\TranslatorDelegatorFactory',
                ],
            ],
            'factories'  => [
                // classes
                StorageInterface::class => Factory\CacheFactory::class,
                SimpleCacheInterface::class => Factory\SimpleCacheFactory::class,   
                PredisInterface::class => Factory\PredisFactory::class,
                EventManagerInterface::class => Factory\EventManagerFactory::class,

                // middlewares
                Middleware\SetLocaleMiddleware::class => Middleware\SetLocaleMiddlewareFactory::class,
                Middleware\JsonBodyParserMiddleware::class => Middleware\JsonBodyParserMiddlewareFactory::class,

                // handlers
                Handler\Locales\FindAllHandler::class => Handler\Locales\FindAllHandlerFactory::class,
                Handler\Files\FindOneByIdHandler::class => Handler\Files\FindOneByIdHandlerFactory::class,
                Handler\Files\ReadOneByIdHandler::class => Handler\Files\ReadOneByIdHandlerFactory::class,

                // models
                Model\CommonModelInterface::class => function ($container) {
                    $config = $container->get('config');
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $cacheStorage = $container->get(StorageInterface::class);
                    return new Model\CommonModel($dbAdapter, $cacheStorage, $config);
                },
                Model\FileModelInterface::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $employeeFiles = new TableGateway('employeeFiles', $dbAdapter, null, new ResultSet(ResultSet::TYPE_ARRAY));
                    return new Model\FileModel($dbAdapter, $employeeFiles);
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
