<?php

/*
 * This file is part of the BenGorUser package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\BenGorUser\UserBundle\DependencyInjection\Compiler;

use BenGorUser\UserBundle\DependencyInjection\Compiler\SecurityServicesPass;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Spec file of security services compiler pass.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SecurityServicesPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SecurityServicesPass::class);
    }

    function it_implmements_compiler_pass_interface()
    {
        $this->shouldImplement(CompilerPassInterface::class);
    }

    function it_processes(ContainerBuilder $container, Definition $definition)
    {
        $container->getParameter('bengor_user.config')->shouldBeCalled()->willReturn([
            'user_class' => [
                'user' => [
                    'class'         => 'AppBundle\Entity\User',
                    'firewall'      => 'main',
                    'persistence'   => 'doctrine_orm',
                    'default_roles' => [
                        'ROLE_USER',
                    ],
                    'use_cases'     => [
                        'security'        => [
                            'enabled'     => true,
                            'api_enabled' => false,
                        ],
                        'sign_up'         => [
                            'enabled' => true,
                            'type'    => 'default',
                        ],
                        'change_password' => [
                            'enabled' => true,
                            'type'    => 'default',
                        ],
                        'remove'          => [
                            'enabled' => true,
                        ],
                    ],
                    'routes'        => [
                        'security'                  => [
                            'login'                     => [
                                'name' => 'bengor_user_user_login',
                                'path' => '/user/login',
                            ],
                            'login_check'               => [
                                'name' => 'bengor_user_user_login_check',
                                'path' => '/user/login_check',
                            ],
                            'logout'                    => [
                                'name' => 'bengor_user_user_logout',
                                'path' => '/user/logout',
                            ],
                            'success_redirection_route' => [
                                'route' => 'bengor_user_user_homepage',
                            ],
                            'jwt'                       => [
                                'name' => 'bengor_user_user_jwt_new_token',
                                'path' => '/user/api/token',
                            ],
                        ],
                        'sign_up'                   => [
                            'name'                      => 'bengor_user_user_sign_up',
                            'path'                      => '/user/sign-up',
                            'success_redirection_route' => 'bengor_user_user_homepage',
                        ],
                        'invite'                    => [
                            'name'                      => 'bengor_user_user_invite',
                            'path'                      => '/user/invite',
                            'success_redirection_route' => null,
                        ],
                        'enable'                    => [
                            'name'                      => 'bengor_user_user_enable',
                            'path'                      => '/user/confirmation-token',
                            'success_redirection_route' => null,
                        ],
                        'change_password'           => [
                            'name'                      => 'bengor_user_user_change_password',
                            'path'                      => '/user/change-password',
                            'success_redirection_route' => null,
                        ],
                        'request_remember_password' => [
                            'name'                      => 'bengor_user_user_request_remember_password',
                            'path'                      => '/user/remember-password',
                            'success_redirection_route' => null,
                        ],
                        'remove'                    => [
                            'name'                      => 'bengor_user_user_remove',
                            'path'                      => '/user/remove',
                            'success_redirection_route' => null,
                        ],
                    ],
                ],
            ],
        ]);

        $container->getDefinition('router.default')->shouldBeCalled()->willReturn($definition);
        $container->getDefinition('bengor_user.user.command_bus')
            ->shouldBeCalled()->willReturn($definition);
        $definition->setPublic(false)->shouldBeCalled()->willReturn($definition);
        $container->setDefinition(
            'bengor.user_bundle.security.authenticator.form_login_user_authenticator',
            Argument::type(Definition::class)
        )->shouldBeCalled()->willReturn($definition);
        $container->setAlias(
            'bengor_user.user.form_login_authenticator',
            'bengor.user_bundle.security.authenticator.form_login_user_authenticator'
        )->shouldBeCalled();

        $container->getDefinition('bengor.user_bundle.security.user_symfony_data_transformer')
            ->shouldBeCalled()->willReturn($definition);
        $definition->setPublic(false)->shouldBeCalled()->willReturn($definition);
        $container->setDefinition(
            'bengor.user_bundle.security.user_symfony_data_transformer',
            Argument::type(Definition::class)
        )->shouldBeCalled()->willReturn($definition);
        $container->setAlias(
            'bengor_user.user.symfony_data_transformer',
            'bengor.user_bundle.security.user_symfony_data_transformer'
        )->shouldBeCalled();

        $container->getDefinition('bengor.user_bundle.security.user_symfony_data_transformer')
            ->shouldBeCalled()->willReturn($definition);
        $container->getDefinition('bengor.user.application.query.user_of_email')
            ->shouldBeCalled()->willReturn($definition);
        $definition->setPublic(false)->shouldBeCalled()->willReturn($definition);
        $container->setDefinition(
            'bengor.user_bundle.security.user_provider',
            Argument::type(Definition::class)
        )->shouldBeCalled()->willReturn($definition);
        $container->setAlias(
            'bengor_user.user.provider',
            'bengor.user_bundle.security.user_provider'
        )->shouldBeCalled();

        $this->process($container);
    }
}
