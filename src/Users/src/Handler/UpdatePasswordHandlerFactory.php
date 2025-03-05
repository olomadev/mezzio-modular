<?php

declare(strict_types=1);

namespace Authentication\Handler\Users;

use Authentication\Model\UserModel;
use Authentication\Filter\Users\PasswordSaveFilter;
use Olobase\Mezzio\Error\ErrorWrapperInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\InputFilter\InputFilterPluginManager;

class UpdatePasswordHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(PasswordSaveFilter::class);

        return new UpdatePasswordHandler(
            $container->get(UserModel::class),
            $inputFilter,
            $container->get(ErrorWrapperInterface::class)
        );
    }
}
