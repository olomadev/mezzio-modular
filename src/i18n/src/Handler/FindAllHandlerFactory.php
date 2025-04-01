<?php

declare(strict_types=1);

namespace i18n\Handler;

use i18n\Model\i18nModelInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FindAllHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new FindAllHandler($container->get(i18nModelInterface::class));
    }
}
