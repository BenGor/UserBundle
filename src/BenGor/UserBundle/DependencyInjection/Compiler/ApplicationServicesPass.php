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

use BenGor\UserBundle\DependencyInjection\Compiler\Application\Service\ChangeUserPasswordServiceBuilder;
use BenGor\UserBundle\DependencyInjection\Compiler\Application\Service\LogInUserServiceBuilder;
use BenGor\UserBundle\DependencyInjection\Compiler\Application\Service\LogOutUserServiceBuilder;
use BenGor\UserBundle\DependencyInjection\Compiler\Application\Service\RemoveUserServiceBuilder;
use BenGor\UserBundle\DependencyInjection\Compiler\Application\Service\SignUpUserServiceBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Register application services compiler pass.
 *
 * Service declaration via PHP allows more
 * flexibility with customization extend users.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ApplicationServicesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('bengor_user.config');

        foreach ($config['user_class'] as $key => $user) {
            (new LogInUserServiceBuilder(
                $container, $user['persistence'], array_merge(
                    $user['use_cases']['security'], [
                        'routes' => $user['routes']['security'],
                    ]
                )
            ))->build($key);

            (new LogOutUserServiceBuilder(
                $container, $user['persistence'], $user['use_cases']['security']
            ))->build($key);

            (new SignUpUserServiceBuilder(
                $container, $user['persistence'], $user['use_cases']['sign_up']
            ))->build($key);

            (new ChangeUserPasswordServiceBuilder(
                $container, $user['persistence'], $user['use_cases']['change_password']
            ))->build($key);

            (new RemoveUserServiceBuilder(
                $container, $user['persistence'], $user['use_cases']['remove']
            ))->build($key);
        }
    }
}
