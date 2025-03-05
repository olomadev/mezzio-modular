<?php

declare(strict_types=1);

namespace Authentication\Handler\Users;

use Authentication\Model\UserModel;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FindAllHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new FindAllHandler($container->get(UserModel::class));
    }
}
