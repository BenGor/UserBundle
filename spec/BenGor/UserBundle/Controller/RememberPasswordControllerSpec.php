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

namespace spec\BenGor\UserBundle\Controller;

use BenGor\UserBundle\Controller\RememberPasswordController;
use PhpSpec\ObjectBehavior;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Spec file of remember password controller.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class RememberPasswordControllerSpec extends ObjectBehavior
{
    function let(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RememberPasswordController::class);
    }

    function it_extends_controller()
    {
        $this->shouldHaveType(Controller::class);
    }
}