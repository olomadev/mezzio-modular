<?php

declare(strict_types=1);

namespace Common\Middleware;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Laminas\I18n\Translator\TranslatorInterface;

class JsonBodyParserMiddlewareFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new JsonBodyParserMiddleware($container->get(TranslatorInterface::class));
    }
}