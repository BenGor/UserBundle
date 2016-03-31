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

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Load routes compiler pass.
 *
 * Based on configuration the routes are created dynamically.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class RoutesCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->processEnableUserRoutes($container);
        $this->processInvitationRoutes($container);
        $this->processSecurityRoutes($container);
        $this->processRegistrationRoutes($container);
    }

    /**
     * Process the activate account routes.
     *
     * @param ContainerBuilder $container The container
     */
    private function processEnableUserRoutes(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('bengor.user_bundle.routing.enable_user_routes_loader')) {
            return;
        }
        $config = $container->getParameter('bengor_user.config');
        $config = $this->buildRegistrationConfiguration($config);

        $container->setParameter('bengor_user.config', $config);
        $container->getDefinition(
            'bengor.user_bundle.routing.enable_user_routes_loader'
        )->replaceArgument(0, array_unique($config['user_class'], SORT_REGULAR));
    }

    /**
     * Process the security routes.
     *
     * @param ContainerBuilder $container The container
     */
    private function processSecurityRoutes(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('bengor.user_bundle.routing.security_routes_loader')) {
            return;
        }
        $config = $container->getParameter('bengor_user.config');
        foreach ($config['user_class'] as $key => $user) {
            $securityRoutes = $user['routes']['security'];

            if (null === $securityRoutes['login']['name']) {
                $config['user_class'][$key]['routes']['security']['login']['name'] = 'bengor_user_' . $key . '_security_login';
            }
            if (null === $securityRoutes['login']['path']) {
                $config['user_class'][$key]['routes']['security']['login']['path'] = '/' . $key . '/login';
            }
            if (null === $securityRoutes['login_check']['name']) {
                $config['user_class'][$key]['routes']['security']['login_check']['name'] = 'bengor_user_' . $key . '_security_login_check';
            }
            if (null === $securityRoutes['login_check']['path']) {
                $config['user_class'][$key]['routes']['security']['login_check']['path'] = '/' . $key . '/login_check';
            }
            if (null === $securityRoutes['logout']['name']) {
                $config['user_class'][$key]['routes']['security']['logout']['name'] = 'bengor_user_' . $key . '_security_logout';
            }
            if (null === $securityRoutes['logout']['path']) {
                $config['user_class'][$key]['routes']['security']['logout']['path'] = '/' . $key . '/logout';
            }
            if (null === $securityRoutes['success_redirection_route']) {
                $config['user_class'][$key]['routes']['security']['success_redirection_route'] = 'bengor_user_' . $key . '_homepage';
            }
        }
        $container->setParameter('bengor_user.config', $config);
        $container->getDefinition(
            'bengor.user_bundle.routing.security_routes_loader'
        )->replaceArgument(0, array_unique($config['user_class'], SORT_REGULAR));
    }

    /**
     * Process the registration by invitation routes.
     *
     * @param ContainerBuilder $container The container
     */
    private function processInvitationRoutes(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('bengor.user_bundle.routing.invitation_routes_loader')) {
            return;
        }
        $config = $container->getParameter('bengor_user.config');
        $config = $this->buildRegistrationConfiguration($config);

        $container->setParameter('bengor_user.config', $config);
        $container->getDefinition(
            'bengor.user_bundle.routing.invitation_routes_loader'
        )->replaceArgument(0, array_unique($config['user_class'], SORT_REGULAR));
    }

    /**
     * Process the registration routes.
     *
     * @param ContainerBuilder $container The container
     */
    private function processRegistrationRoutes(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('bengor.user_bundle.routing.registration_routes_loader')) {
            return;
        }
        $config = $container->getParameter('bengor_user.config');
        $config = $this->buildRegistrationConfiguration($config);

        $container->setParameter('bengor_user.config', $config);
        $container->getDefinition(
            'bengor.user_bundle.routing.registration_routes_loader'
        )->replaceArgument(0, array_unique($config['user_class'], SORT_REGULAR));
    }

    /**
     * Builds the empty registration route configuration.
     *
     * @param array $config The configurantion tree
     *
     * @return array
     */
    private function buildRegistrationConfiguration($config)
    {
        foreach ($config['user_class'] as $key => $user) {
            $registrationRoutes = $user['routes']['registration'];

            if (null === $registrationRoutes['name']) {
                $config['user_class'][$key]['routes']['registration']['name'] = 'bengor_user_' . $key . '_registration';
            }
            if (null === $registrationRoutes['path']) {
                $config['user_class'][$key]['routes']['registration']['path'] = '/' . $key . '/register';
            }
            if (null === $registrationRoutes['invitation']['name']) {
                $config['user_class'][$key]['routes']['registration']['invitation']['name'] = 'bengor_user_' . $key . '_invite';
            }
            if (null === $registrationRoutes['invitation']['path']) {
                $config['user_class'][$key]['routes']['registration']['invitation']['path'] = '/' . $key . '/invite';
            }
            if (null === $registrationRoutes['user_enable']['name']) {
                $config['user_class'][$key]['routes']['registration']['user_enable']['name'] = 'bengor_user_' . $key . '_enable';
            }
            if (null === $registrationRoutes['user_enable']['path']) {
                $config['user_class'][$key]['routes']['registration']['user_enable']['path'] = '/' . $key . '/confirmation-token';
            }
            if (null === $registrationRoutes['success_redirection_route']) {
                $config['user_class'][$key]['routes']['registration']['success_redirection_route'] = 'bengor_user_' . $key . '_homepage';
            }
        }

        return $config;
    }
}
