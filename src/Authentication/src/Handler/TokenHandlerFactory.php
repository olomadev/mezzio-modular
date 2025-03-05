<?php

declare(strict_types=1);

namespace Authentication\Handler;

use Authentication\InputFilter\TokenFilter;
use Olobase\Mezzio\Error\ErrorWrapperInterface;
use Mezzio\Authentication\AuthenticationInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\InputFilter\InputFilterPluginManager;
use Laminas\EventManager\EventManagerInterface;
use Authentication\EventListener\LoginListener;

class TokenHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter = $pluginManager->get(TokenFilter::class);
        $loginListener = $container->get(LoginListener::class);
        //
        // attach login events
        // 
        $eventManager = $container->get(EventManagerInterface::class);
        $loginListener->attach($eventManager);

        return new TokenHandler(
            $container->get('config'),
            $container->get(AuthenticationInterface::class),
            $inputFilter,
            $container->get(ErrorWrapperInterface::class)
        );
    }
}