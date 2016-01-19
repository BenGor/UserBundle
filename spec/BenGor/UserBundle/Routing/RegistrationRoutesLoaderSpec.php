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

namespace spec\BenGor\UserBundle\Routing;

use BenGor\UserBundle\Routing\RegistrationRoutesLoader;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Spec file of registration routes loader class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class RegistrationRoutesLoaderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([
            [
                'register_path' => '/register',
                'action'        => 'register',
                'userClass'     => 'user',
                'firewall'      => 'main',
                'pattern'       => '',
            ],
            [
                'invite_path' => '/invite',
                'action'      => 'invite',
                'userClass'   => 'user',
                'firewall'    => 'main',
            ],
        ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RegistrationRoutesLoader::class);
    }

    function it_implements_loader_interface()
    {
        $this->shouldHaveType(LoaderInterface::class);
    }

    function it_loads()
    {
        $this->load('resource');
    }
}
