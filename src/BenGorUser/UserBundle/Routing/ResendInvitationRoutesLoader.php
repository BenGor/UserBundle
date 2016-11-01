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

namespace BenGorUser\UserBundle\Routing;

use Symfony\Component\Routing\Route;

/**
 * Resend invitation user routes loader class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ResendInvitationRoutesLoader extends RoutesLoader
{
    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return 'bengor_user_resend_invitation' === $type;
    }

    /**
     * {@inheritdoc}
     */
    protected function register($user, array $config)
    {
        if ('default' === $config['type'] || 'with_confirmation' === $config['type']) {
            return;
        }

        $this->routes->add(
            $config['name'],
            new Route(
                $config['path'],
                [
                    '_controller' => 'BenGorUserBundle:Invite:resendInvitation',
                    'userClass'   => $user,
                ],
                [],
                [],
                '',
                [],
                ['GET', 'POST']
            )
        );
    }
}
