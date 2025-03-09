<?php

declare(strict_types=1);

namespace Authorization\Handler\Actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FindAllHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new FindAllHandler;
    }
}
