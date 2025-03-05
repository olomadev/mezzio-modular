<?php

declare(strict_types=1);

namespace Authentication\Handler\Users;

use Authentication\Model\UserModel;
use Olobase\Mezzio\DataManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FindOneByIdHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new FindOneByIdHandler(
            $container->get(UserModel::class),
            $container->get(DataManagerInterface::class)
        );
    }
}
