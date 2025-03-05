<?php

declare(strict_types=1);

namespace Authentication\Handler;

use Authentication\Model\TokenModel;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\I18n\Translator\TranslatorInterface;

class LogoutHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new LogoutHandler(
            $container->get(TokenModel::class), 
            $container->get(TranslatorInterface::class)
        );
    }
}
