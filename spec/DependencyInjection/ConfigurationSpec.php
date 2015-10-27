<?php

/*
 * This file is part of the User bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\BenGor\UserBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;

/**
 * Spec file of configuration class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ConfigurationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BenGor\UserBundle\DependencyInjection\Configuration');
    }

    function it_implements_symfony_configuration_interface()
    {
        $this->shouldHaveType('Symfony\Component\Config\Definition\ConfigurationInterface');
    }

    function it_gets_config_tree_builder()
    {
        $this->getConfigTreeBuilder()->shouldReturnAnInstanceOf(
            'Symfony\Component\Config\Definition\Builder\TreeBuilder'
        );
    }
}
