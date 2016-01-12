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

namespace BenGor\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * BenGor user bundle configuration class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('ben_gor_user')
            ->children()
                ->arrayNode('user_class')->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('class')
                                ->isRequired(true)
                            ->end()
                            ->arrayNode('firewall')
                                ->children()
                                    ->scalarNode('name')
                                        ->isRequired(true)
                                    ->end()
                                    ->scalarNode('pattern')
                                        ->defaultValue('')
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('registration')
                                ->children()
                                    ->scalarNode('type')
                                        ->defaultValue('default')
                                        ->validate()
                                        ->ifNotInArray(['default', 'none', 'by_invitation'])
                                            ->thenInvalid('Invalid registration type "%s"')
                                        ->end()
                                    ->end()
                                    ->scalarNode('path')
                                        ->defaultValue('/register')
                                    ->end()
                                    ->scalarNode('invite_path')
                                        ->defaultValue('/invite')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
