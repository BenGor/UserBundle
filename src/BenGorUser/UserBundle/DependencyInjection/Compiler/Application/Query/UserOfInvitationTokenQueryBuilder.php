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

namespace BenGorUser\UserBundle\DependencyInjection\Compiler\Application\Query;

use BenGorUser\User\Application\Query\UserOfInvitationTokenHandler;
use Symfony\Component\DependencyInjection\Definition;

/**
 * User of invitation token query builder.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserOfInvitationTokenQueryBuilder extends QueryBuilder
{
    /**
     * {@inheritdoc}
     */
    public function register($user)
    {
        $this->container->setDefinition(
            $this->definitionName($user),
            (new Definition(
                UserOfInvitationTokenHandler::class, [
                    $this->container->getDefinition(
                        'bengor.user.infrastructure.persistence.' . $user . '_repository'
                    ),
                    $this->container->getDefinition(
                        'bengor.user.application.data_transformer.' . $user . '_dto'
                    ),
                ]
            ))->setPublic(false)
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function definitionName($user)
    {
        return 'bengor.user.application.query.' . $user . '_of_invitation_token';
    }

    /**
     * {@inheritdoc}
     */
    protected function aliasDefinitionName($user)
    {
        return 'bengor_user.' . $user . '.by_invitation_token_query';
    }
}
