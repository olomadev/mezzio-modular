<?php

declare(strict_types=1);

namespace Authentication\Authentication;

use Authorization\Model\RoleModelInterface;
use Authentication\Model\TokenModelInterface;
use Psr\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\EventManager\EventManagerInterface;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Olobase\Mezzio\Authentication\JwtEncoderInterface;
use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter;
use Mezzio\Authentication\Exception;
use Mezzio\Authentication\UserInterface;

class JwtAuthenticationFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config') ?? [];
        if (! $container->has(UserInterface::class)) {
            throw new Exception\InvalidConfigException(
                'UserInterface factory service is missing for authentication'
            );
        }
        $passwordValidation = function ($hash, $password) {
            return password_verify($password, $hash);
        };
        $authAdapter = new AuthenticationAdapter(  // CallbackCheckAdapter
            $container->get(Adapter::class),
            $config['authentication']['tablename'],
            $config['authentication']['username'],
            $config['authentication']['password'],
            $passwordValidation
        );
        return new JwtAuthentication(
            $config,
            $authAdapter,
            $container->get(TranslatorInterface::class),
            $container->get(JwtEncoderInterface::class),
            $container->get(TokenModelInterface::class),
            $container->get(RoleModelInterface::class),
            $container->get(EventManagerInterface::class),
            $container->has(UserInterface::class) ? $container->get(UserInterface::class) : null
        );
    }
}
