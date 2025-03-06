<?php

declare(strict_types=1);

namespace Categories\Handler;

use Categories\Model\CategoryModelInterface;
use Categories\Filter\DeleteFilter;
use Olobase\Mezzio\Error\ErrorWrapperInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\InputFilter\InputFilterPluginManager;

class DeleteHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(DeleteFilter::class);

        return new DeleteHandler(
            $container->get(CategoryModelInterface::class),
            $inputFilter,
            $container->get(ErrorWrapperInterface::class)
        );
    }
}
