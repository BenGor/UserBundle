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

use BenGor\User\Infrastructure\Mailing\Mandrill\MandrillUserMailer;
use BenGor\User\Infrastructure\Mailing\SwiftMailer\SwiftMailerUserMailer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\ExpressionLanguage\Expression;

/**
 * Register mailing services compiler pass.
 *
 * Service declaration via PHP allows more
 * flexibility with customization extend users.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class MailingServicesCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->swiftMailer($container);
        $this->mandrill($container);
    }

    /**
     * Loads the Swift Mailer user mailer service.
     *
     * @param ContainerBuilder $container The container builder
     */
    private function swiftMailer(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('mailer')) {
            return;
        }

        $container->setDefinition(
            'bengor.user.infrastructure.mailing.swift_mailer.user_mailer',
            new Definition(
                SwiftMailerUserMailer::class, [
                    $container->getDefinition(
                        'mailer'
                    ),
                ]
            )
        );
    }

    /**
     * Loads the Mandrill user mailer service.
     *
     * @param ContainerBuilder $container The container builder
     */
    private function mandrill(ContainerBuilder $container)
    {
        if (!class_exists('\Mandrill')) {
            return;
        }

        $container->setDefinition(
            'mandrill',
            new Definition(
                \Mandrill::class, [
                    new Expression(
                        "container.hasParameter('mandrill_api_key') ? parameter('mandrill_api_key') : null"
                    ),
                ]
            )
        )->setPublic(false);

        $container->setDefinition(
            'bengor.user.infrastructure.mailing.mandrill.user_mailer',
            new Definition(
                MandrillUserMailer::class, [
                    $container->getDefinition(
                        'mandrill'
                    ),
                ]
            )
        );
    }
}