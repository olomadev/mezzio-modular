<?php

declare(strict_types=1);

namespace Authentication\Handler\FailedLogins;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Authentication\Model\FailedLoginModelInterface;

class FindAllByPagingHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new FindAllByPagingHandler($container->get(FailedLoginModelInterface::class));
    }
}
