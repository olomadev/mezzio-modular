<?php

declare(strict_types=1);

namespace Authentication\Handler\FailedLogins;

use Authentication\Model\FailedLoginModel;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FindAllByPagingHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new FindAllByPagingHandler($container->get(FailedLoginModel::class));
    }
}
