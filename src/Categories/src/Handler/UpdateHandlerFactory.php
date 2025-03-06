<?php

declare(strict_types=1);

namespace Categories\Handler;

use Categories\Model\CategoryModelInterface;
use Categories\Filter\SaveFilter;
use Olobase\Mezzio\DataManagerInterface;
use Olobase\Mezzio\Error\ErrorWrapperInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\InputFilter\InputFilterPluginManager;

class UpdateHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(SaveFilter::class);

        return new UpdateHandler(
            $container->get(CategoryModelInterface::class),
            $container->get(DataManagerInterface::class),
            $inputFilter,
            $container->get(ErrorWrapperInterface::class)
        );
    }
}
