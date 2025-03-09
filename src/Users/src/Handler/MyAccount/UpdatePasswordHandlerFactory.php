<?php

declare(strict_types=1);

namespace Users\Handler\MyAccount;

use Users\Model\UserModelInterface;
use Users\Filter\Account\PasswordChangeFilter;
use Olobase\Mezzio\Error\ErrorWrapperInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\InputFilter\InputFilterPluginManager;

class UpdatePasswordHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(PasswordChangeFilter::class);

        return new UpdatePasswordHandler(
            $container->get(UserModelInterface::class),
            $inputFilter,
            $container->get(ErrorWrapperInterface::class)
        );
    }
}
