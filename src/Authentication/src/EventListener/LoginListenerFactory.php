<?php

declare(strict_types=1);

namespace Authentication\EventListener;

use Authentication\Model\FailedLoginModelInterface;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class LoginListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new LoginListener($container->get(FailedLoginModelInterface::class));
    }
}
