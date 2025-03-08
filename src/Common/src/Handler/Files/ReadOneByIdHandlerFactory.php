<?php

declare(strict_types=1);

namespace Common\Handler\Files;

use Common\Model\FileModelInterface;
use Common\Filter\Files\ReadFileFilter;
use Psr\Container\ContainerInterface;
use Laminas\InputFilter\InputFilterPluginManager;
use Olobase\Mezzio\Error\ErrorWrapperInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\I18n\Translator\TranslatorInterface;

class ReadOneByIdHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter   = $pluginManager->get(ReadFileFilter::class);

        return new ReadOneByIdHandler(
            $container->get(TranslatorInterface::class),
            $container->get(FileModelInterface::class),
            $inputFilter,
            $container->get(ErrorWrapperInterface::class)
        );
    }
}
