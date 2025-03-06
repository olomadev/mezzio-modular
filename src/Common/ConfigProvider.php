<?php

declare(strict_types=1);

namespace Common;

use Laminas\Cache\Storage\StorageInterface;
use Predis\ClientInterface as PredisInterface;
use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;
use Laminas\EventManager\EventManagerInterface;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\ServiceManager\Factory\InvokableFactory;

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
            'invokables' => [
                
            ],
            'delegators' => [
                TranslatorInterface::class => [
                    'Common\Factory\TranslatorDelegatorFactory',
                ],
            ],
            'factories'  => [
                // middlewares
                Middleware\SetLocaleMiddleware::class => Middleware\SetLocaleMiddlewareFactory::class,
                Middleware\JsonBodyParserMiddleware::class => Middleware\JsonBodyParserMiddlewareFactory::class,

                // other factories
                StorageInterface::class => Factory\CacheFactory::class,
                SimpleCacheInterface::class => Factory\SimpleCacheFactory::class,   
                PredisInterface::class => Factory\PredisFactory::class,
                EventManagerInterface::class => Factory\EventManagerFactory::class,
            ],
        ];
    }
}
