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

namespace spec\BenGorUser\UserBundle\DependencyInjection\Compiler;

use BenGorUser\User\Domain\Model\User;
use BenGorUser\UserBundle\DependencyInjection\Compiler\RoutesPass;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Spec file of load routes compiler pass.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class RoutesPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(RoutesPass::class);
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
                            'enabled' => true,
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
                            'success_redirection_route' => 'bengor_user_user_homepage',
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

        $container->hasDefinition('bengor.user_bundle.routing.change_password_routes_loader')
            ->shouldBeCalled()->willReturn(true);
        $container->getDefinition('bengor.user_bundle.routing.change_password_routes_loader')
            ->shouldBeCalled()->willReturn($definition);
        $container->hasDefinition('bengor.user_bundle.routing.enable_routes_loader')
            ->shouldBeCalled()->willReturn(true);
        $container->getDefinition('bengor.user_bundle.routing.enable_routes_loader')
            ->shouldBeCalled()->willReturn($definition);
        $container->hasDefinition('bengor.user_bundle.routing.invite_routes_loader')
            ->shouldBeCalled()->willReturn(true);
        $container->getDefinition('bengor.user_bundle.routing.invite_routes_loader')
            ->shouldBeCalled()->willReturn($definition);
        $container->hasDefinition('bengor.user_bundle.routing.security_routes_loader')
            ->shouldBeCalled()->willReturn(true);
        $container->getDefinition('bengor.user_bundle.routing.security_routes_loader')
            ->shouldBeCalled()->willReturn($definition);
        $container->hasDefinition('bengor.user_bundle.routing.sign_up_routes_loader')
            ->shouldBeCalled()->willReturn(true);
        $container->getDefinition('bengor.user_bundle.routing.sign_up_routes_loader')
            ->shouldBeCalled()->willReturn($definition);
        $container->hasDefinition('bengor.user_bundle.routing.remove_routes_loader')
            ->shouldBeCalled()->willReturn(true);
        $container->getDefinition('bengor.user_bundle.routing.remove_routes_loader')
            ->shouldBeCalled()->willReturn($definition);
        $container->hasDefinition('bengor.user_bundle.routing.request_remember_password_routes_loader')
            ->shouldBeCalled()->willReturn(true);
        $container->getDefinition('bengor.user_bundle.routing.request_remember_password_routes_loader')
            ->shouldBeCalled()->willReturn($definition);

        $this->process($container);
    }
}
