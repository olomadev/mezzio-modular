<?php

declare(strict_types=1);

namespace Categories\Handler;

use Categories\Model\CategoryModelInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FindAllByPagingHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new FindAllByPagingHandler($container->get(CategoryModelInterface::class));
    }
}
