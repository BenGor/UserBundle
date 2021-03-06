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
 * Change user password routes loader class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ChangePasswordRoutesLoader extends RoutesLoader
{
    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return 'bengor_user_change_password' === $type;
    }

    /**
     * {@inheritdoc}
     */
    protected function register($user, array $config)
    {
        $this->routes->add(
            $config['name'],
            new Route(
                $config['path'],
                [
                    '_controller'  => 'BenGorUserBundle:ChangePassword:' . $config['type'],
                    'userClass'    => $user,
                    'successRoute' => $config['success_redirection_route'],
                ],
                [],
                [],
                '',
                [],
                ['GET', 'POST']
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function sanitize($specificationName)
    {
        if ('by_request_remember_password' === $specificationName
            || 'byRequestRememberPassword' === $specificationName
        ) {
            return 'byRequestRememberPassword';
        }
        if ('default' === $specificationName) {
            return 'default';
        }
        throw new \RuntimeException('Given change password type is not supported');
    }
}
