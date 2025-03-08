<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

// Cache configuration
$cacheConfig = [
    'config_cache_path' => 'data/cache/config-cache.php',
];

// Let's merge the configuration with ConfigAggregator
$aggregator = new ConfigAggregator([
  \Olobase\Mezzio\ConfigProvider::class,
  \Mezzio\Authorization\ConfigProvider::class,
  \Categories\ConfigProvider::class,
  \Users\ConfigProvider::class,
  \Laminas\Serializer\ConfigProvider::class,
  \Laminas\Cache\ConfigProvider::class,
  \Laminas\Cache\Storage\Adapter\Redis\ConfigProvider::class,
  \Laminas\Paginator\ConfigProvider::class,
  \Laminas\Mvc\I18n\ConfigProvider::class,
  \Laminas\I18n\ConfigProvider::class,
  \Laminas\Db\ConfigProvider::class,
  \Mezzio\Authentication\LaminasAuthentication\ConfigProvider::class,
  \Mezzio\Authentication\ConfigProvider::class,
  \Laminas\InputFilter\ConfigProvider::class,
  \Laminas\Filter\ConfigProvider::class,
  \Authorization\ConfigProvider::class,
  \Authentication\ConfigProvider::class,
  \Mezzio\Tooling\ConfigProvider::class,
  \Mezzio\Router\LaminasRouter\ConfigProvider::class,
  \Laminas\Router\ConfigProvider::class,
  \Laminas\HttpHandlerRunner\ConfigProvider::class,
  \Laminas\Validator\ConfigProvider::class,
  
  // Cache config
  new ArrayProvider($cacheConfig),
  \Mezzio\Helper\ConfigProvider::class,
  \Mezzio\ConfigProvider::class,
  \Mezzio\Router\ConfigProvider::class,
  \Laminas\Diactoros\ConfigProvider::class,
  // Swoole config
  class_exists(\Mezzio\Swoole\ConfigProvider::class)
      ? \Mezzio\Swoole\ConfigProvider::class
      : function (): array {
          return [];
      },
  // Common config provider
  Common\ConfigProvider::class,
  // Load application configurations in a specific order
  new PhpFileProvider(realpath(__DIR__) . sprintf('/autoload/{,*.}{global,%s}.php', getenv('APP_ENV') ?: 'local')),
  // If there is a development configuration, install it
  new PhpFileProvider(realpath(__DIR__) . '/development.config.php'),
], $cacheConfig['config_cache_path']);

return $aggregator->getMergedConfig();
