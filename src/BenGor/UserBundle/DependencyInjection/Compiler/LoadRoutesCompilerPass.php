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
class LoadRoutesCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('bengor.user_bundle.routing.security_routes_loader')) {
            return;
        }

        $config = $container->getParameter('bengor_user.config');

        $patterns = [];
        foreach ($config['user_class'] as $user) {
            $name = '_' . $user['firewall']['pattern'];
            if ('' === $pattern = $user['firewall']['pattern']) {
                $name = '';
            }
            $patterns[$name] = $pattern;
        }

        $container->getDefinition(
            'bengor.user_bundle.routing.security_routes_loader'
        )->replaceArgument(0, array_unique($patterns));
    }
}
