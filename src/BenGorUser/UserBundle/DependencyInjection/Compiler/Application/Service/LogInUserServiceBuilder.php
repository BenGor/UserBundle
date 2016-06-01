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

namespace BenGorUser\UserBundle\DependencyInjection\Compiler\Application\Service;

use BenGorUser\User\Application\Service\LogIn\LogInUserCommand;
use BenGorUser\User\Application\Service\LogIn\LogInUserHandler;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Log in user service builder.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class LogInUserServiceBuilder extends ServiceBuilder
{
    /**
     * {@inheritdoc}
     */
    public function register($user)
    {
        $this->container->setDefinition(
            $this->definitionName($user),
            (new Definition(
                LogInUserHandler::class, [
                $this->container->getDefinition(
                    'bengor.user.infrastructure.persistence.' . $user . '_repository'
                ),
                $this->container->getDefinition(
                    'bengor.user.infrastructure.security.symfony.' . $user . '_password_encoder'
                ),
            ])
            )->addTag('bengor_user_' . $user . '_command_bus_handler', [
                'handles' => LogInUserCommand::class,
            ])
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function definitionName($user)
    {
        return 'bengor.user.application.service.log_in_' . $user;
    }

    /**
     * {@inheritdoc}
     */
    protected function aliasDefinitionName($user)
    {
        return 'bengor_user.log_in_' . $user;
    }
}
