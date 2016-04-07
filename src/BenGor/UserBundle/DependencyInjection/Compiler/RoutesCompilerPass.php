<?php

/*
 * This file is part of the BenGorUserBundle bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGor\UserBundle\DependencyInjection\Compiler;

use BenGor\UserBundle\DependencyInjection\Compiler\Routing\ChangePasswordRoutesLoaderBuilder;
use BenGor\UserBundle\DependencyInjection\Compiler\Routing\EnableRoutesLoaderBuilder;
use BenGor\UserBundle\DependencyInjection\Compiler\Routing\InviteRoutesLoaderBuilder;
use BenGor\UserBundle\DependencyInjection\Compiler\Routing\RemoveRoutesLoaderBuilder;
use BenGor\UserBundle\DependencyInjection\Compiler\Routing\RequestRememberPasswordRoutesLoaderBuilder;
use BenGor\UserBundle\DependencyInjection\Compiler\Routing\SecurityRoutesLoaderBuilder;
use BenGor\UserBundle\DependencyInjection\Compiler\Routing\SignUpRoutesLoaderBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Load routes compiler pass.
 *
 * Based on configuration the routes are created dynamically.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class RoutesCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('bengor_user.config');

        $changePasswordConfiguration = [];
        $enableConfiguration = [];
        $inviteConfiguration = [];
        $securityConfiguration = [];
        $signUpConfiguration = [];
        $removeConfiguration = [];
        $requestRememberPasswordConfiguration = [];

        foreach ($config['user_class'] as $key => $user) {
            $changePasswordConfiguration[$key] = array_merge(
                $user['use_cases']['change_password'],
                $user['routes']['change_password']
            );

            $enableConfiguration[$key] = array_merge(
                $user['use_cases']['sign_up'],
                $user['routes']['enable']
            );
            $inviteConfiguration[$key] = array_merge(
                $user['use_cases']['sign_up'],
                $user['routes']['invite']
            );
            $securityConfiguration[$key] = array_merge(
                $user['use_cases']['security'],
                $user['routes']['security']
            );
            $signUpConfiguration[$key] = array_merge(
                ['firewall' => $user['firewall']],
                $user['use_cases']['sign_up'],
                $user['routes']['sign_up']
            );
            $removeConfiguration[$key] = array_merge(
                $user['use_cases']['remove'],
                $user['routes']['remove']
            );
            $requestRememberPasswordConfiguration[$key] = array_merge(
                $user['use_cases']['change_password'],
                $user['routes']['request_remember_password']
            );
        }

        (new ChangePasswordRoutesLoaderBuilder($container, $changePasswordConfiguration))->build();
        (new EnableRoutesLoaderBuilder($container, $enableConfiguration))->build();
        (new InviteRoutesLoaderBuilder($container, $inviteConfiguration))->build();
        (new SecurityRoutesLoaderBuilder($container, $securityConfiguration))->build();
        (new SignUpRoutesLoaderBuilder($container, $signUpConfiguration))->build();
        (new RemoveRoutesLoaderBuilder($container, $removeConfiguration))->build();
        (new RequestRememberPasswordRoutesLoaderBuilder($container, $requestRememberPasswordConfiguration))->build();
    }
}
