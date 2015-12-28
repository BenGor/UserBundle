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

namespace spec\BenGor\UserBundle;

use BenGor\UserBundle\BenGorUserBundle;
use BenGor\UserBundle\DependencyInjection\Compiler\AliasServicesCompilerPass;
use BenGor\UserBundle\DependencyInjection\Compiler\ApplicationServicesCompilerPass;
use BenGor\UserBundle\DependencyInjection\Compiler\DomainServicesCompilerPass;
use BenGor\UserBundle\DependencyInjection\Compiler\LoadDoctrineCustomTypesCompilerPass;
use BenGor\UserBundle\DependencyInjection\Compiler\LoadRoutesCompilerPass;
use BenGor\UserBundle\DependencyInjection\Compiler\MailingServicesCompilerPass;
use BenGor\UserBundle\DependencyInjection\Compiler\PersistenceServicesCompilerPass;
use BenGor\UserBundle\DependencyInjection\Compiler\SecurityServicesCompilerPass;
use BenGor\UserBundle\DependencyInjection\Compiler\TransactionalApplicationServicesCompilerPass;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Spec file of bengor user bundle class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class BenGorUserBundleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(BenGorUserBundle::class);
    }

    function it_extends_symfony_bundle()
    {
        $this->shouldHaveType(Bundle::class);
    }

    function it_builds(ContainerBuilder $container)
    {
        $container->addCompilerPass(
            Argument::type(DomainServicesCompilerPass::class),
            PassConfig::TYPE_OPTIMIZE
        )->shouldBeCalled()->willReturn($container);

        $container->addCompilerPass(
            Argument::type(PersistenceServicesCompilerPass::class),
            PassConfig::TYPE_OPTIMIZE
        )->shouldBeCalled()->willReturn($container);

        $container->addCompilerPass(
            Argument::type(SecurityServicesCompilerPass::class),
            PassConfig::TYPE_OPTIMIZE
        )->shouldBeCalled()->willReturn($container);

        $container->addCompilerPass(
            Argument::type(LoadDoctrineCustomTypesCompilerPass::class),
            PassConfig::TYPE_OPTIMIZE
        )->shouldBeCalled()->willReturn($container);

        $container->addCompilerPass(
            Argument::type(MailingServicesCompilerPass::class),
            PassConfig::TYPE_OPTIMIZE
        )->shouldBeCalled()->willReturn($container);

        $container->addCompilerPass(
            Argument::type(ApplicationServicesCompilerPass::class),
            PassConfig::TYPE_OPTIMIZE
        )->shouldBeCalled()->willReturn($container);

        $container->addCompilerPass(
            Argument::type(TransactionalApplicationServicesCompilerPass::class),
            PassConfig::TYPE_OPTIMIZE
        )->shouldBeCalled()->willReturn($container);

        $container->addCompilerPass(
            Argument::type(AliasServicesCompilerPass::class),
            PassConfig::TYPE_OPTIMIZE
        )->shouldBeCalled()->willReturn($container);

        $container->addCompilerPass(
            Argument::type(LoadRoutesCompilerPass::class),
            PassConfig::TYPE_OPTIMIZE
        )->shouldBeCalled()->willReturn($container);

        $container->loadFromExtension('doctrine', [
            'orm' => [
                'mappings' => [
                    'BenGorUserBundle' => [
                        'type'      => 'yml',
                        'is_bundle' => true,
                        'prefix'    => 'BenGor\\User\\Domain\\Model',
                    ],
                ],
            ],
        ])->shouldBeCalled()->willReturn($container);

        $this->build($container);
    }
}