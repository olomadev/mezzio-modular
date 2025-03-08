<?php

declare(strict_types=1);

namespace Common\Handler\Locales;

use Common\Model\CommonModelInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FindAllHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new FindAllHandler($container->get(CommonModelInterface::class));
    }
}
