<?php

declare(strict_types=1);

namespace Authentication\Handler;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Authentication\Model\TokenModelInterface;
use Laminas\I18n\Translator\TranslatorInterface;

class LogoutHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new LogoutHandler(
            $container->get(TranslatorInterface::class),
            $container->get(TokenModelInterface::class) 
        );
    }
}
