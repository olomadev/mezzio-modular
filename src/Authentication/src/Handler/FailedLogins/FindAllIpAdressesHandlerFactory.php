<?php

declare(strict_types=1);

namespace Authentication\Handler\FailedLogins;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Authentication\Model\FailedLoginModelInterface;

class FindAllIpAdressesHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new FindAllIpAdressesHandler($container->get(FailedLoginModelInterface::class));
    }
}
