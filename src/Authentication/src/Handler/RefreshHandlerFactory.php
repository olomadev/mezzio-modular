<?php

declare(strict_types=1);

namespace Authentication\Handler;

use Authentication\Model\TokenModelInterface;
use Olobase\Mezzio\Error\ErrorWrapperInterface;
use Mezzio\Authentication\AuthenticationInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\I18n\Translator\TranslatorInterface;

class RefreshHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new RefreshHandler(
            $container->get('config'), 
            $container->get(TranslatorInterface::class), 
            $container->get(AuthenticationInterface::class), 
            $container->get(TokenModelInterface::class), 
            $container->get(ErrorWrapperInterface::class)
        );
    }
}
