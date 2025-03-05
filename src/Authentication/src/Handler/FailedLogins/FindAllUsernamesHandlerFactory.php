<?php

declare(strict_types=1);

namespace Authentication\Handler\FailedLogins;

use Authentication\Model\FailedLoginModel;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FindAllUsernamesHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new FindAllUsernamesHandler($container->get(FailedLoginModel::class));
    }
}
