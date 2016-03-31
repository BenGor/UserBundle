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

namespace spec\BenGor\UserBundle\DependencyInjection\Compiler;

use BenGor\User\Domain\Model\User;
use BenGor\UserBundle\DependencyInjection\Compiler\AliasServicesCompilerPass;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Spec file of alias services compiler pass.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class AliasServicesCompilerPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(AliasServicesCompilerPass::class);
    }

    function it_implmements_compiler_pass_interface()
    {
        $this->shouldImplement(CompilerPassInterface::class);
    }

    function it_processes(ContainerBuilder $container)
    {
        $container->getParameter('bengor_user.config')->shouldBeCalled()->willReturn([
            'user_class' => [
                'user' => [
                    'class'     => User::class,
                    'firewall'  => 'main',
                    'use_cases' => [
                        'security'     => [
                            'enabled' => true,
                        ],
                        'registration' => [
                            'enabled' => true,
                            'type'    => 'by_invitation',
                        ],
                    ],
                    'routes'    => [
                        'security'     => [
                            'login'                     => [
                                'name' => 'bengor_user_user_security_login',
                                'path' => '/login',
                            ],
                            'login_check'               => [
                                'name' => 'bengor_user_user_security_login_check',
                                'path' => '/login_check',
                            ],
                            'logout'                    => [
                                'name' => 'bengor_user_user_security_logout',
                                'path' => '/logout',
                            ],
                            'success_redirection_route' => 'bengor_user_user_homepage',
                        ],
                        'registration' => [
                            'name'                      => 'bengor_user_user_registration',
                            'path'                      => '/user/register',
                            'success_redirection_route' => 'bengor_user_user_homepage',
                            'invitation'                => [
                                'name' => 'bengor_user_user_invitation',
                                'path' => '/user/invite',
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $container->setAlias(
            'bengor_user.symfony_user_password_encoder',
            'bengor.user.infrastructure.security.symfony.user_password_encoder'
        )->shouldBeCalled();

        $container->setAlias(
            'bengor_user.user_factory',
            'bengor.user.infrastructure.domain.model.user_factory'
        )->shouldBeCalled();
        $container->setAlias(
            'bengor_user.user_repository',
            'bengor.user.infrastructure.persistence.user_repository'
        )->shouldBeCalled();
        $container->setAlias(
            'bengor_user.user_guest_repository',
            'bengor.user.infrastructure.persistence.user_guest_repository'
        )->shouldBeCalled();
        $container->setAlias(
            'bengor_user.enable_user',
            'bengor.user.application.service.enable_user_transactional'
        )->shouldBeCalled();
        $container->setAlias(
            'bengor_user.change_user_password',
            'bengor.user.application.service.change_user_password_transactional'
        )->shouldBeCalled();
        $container->setAlias(
            'bengor_user.change_user_password_using_remember_password_token',
            'bengor.user.application.service.change_user_password_using_remember_password_token_transactional'
        )->shouldBeCalled();
        $container->setAlias(
            'bengor_user.log_in_user',
            'bengor.user.application.service.log_in_user_transactional'
        )->shouldBeCalled();
        $container->setAlias(
            'bengor_user.log_out_user',
            'bengor.user.application.service.log_out_user_transactional'
        )->shouldBeCalled();
        $container->setAlias(
            'bengor_user.invite_user',
            'bengor.user.application.service.invite_user_transactional'
        )->shouldBeCalled();
        $container->setAlias(
            'bengor_user.remove_user',
            'bengor.user.application.service.remove_user_transactional'
        )->shouldBeCalled();
        $container->setAlias(
            'bengor_user.request_user_remember_password_token',
            'bengor.user.application.service.request_user_remember_password_token_transactional'
        )->shouldBeCalled();
        $container->setAlias(
            'bengor_user.sign_up_user',
            'bengor.user.application.service.sign_up_user_transactional'
        )->shouldBeCalled();
        $container->setAlias(
            'bengor_user.form_login_user_authenticator',
            'bengor.user_bundle.security.form_login_user_authenticator'
        )->shouldBeCalled();

        $this->process($container);
    }
}
