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

namespace spec\BenGorUser\UserBundle\Form\Type;

use BenGorUser\UserBundle\Form\Type\SignUpType;
use BenGorUser\UserBundle\Form\Type\SignUpWithConfirmationType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Spec file of SignUpWithConfirmationType class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SignUpWithConfirmationTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SignUpWithConfirmationType::class);
    }

    function it_extends_sign_up_type()
    {
        $this->shouldHaveType(SignUpType::class);
    }

    function it_builds_form(FormBuilderInterface $builder)
    {
        $builder->add('email', EmailType::class)->shouldBeCalled()->willReturn($builder);
        $builder->add('password', RepeatedType::class, [
            'type'            => PasswordType::class,
            'invalid_message' => 'sign_up.form_password_invalid_message',
            'first_options'   => ['label' => 'sign_up.form_password_first_option_label'],
            'second_options'  => ['label' => 'sign_up.form_password_second_option_label'],
        ])->shouldBeCalled()->willReturn($builder);
        $builder->add('submit', SubmitType::class, [
            'label' => 'sign_up.form_submit_button',
        ])->shouldBeCalled()->willReturn($builder);

        $this->buildForm($builder, ['roles' => ['ROLE_USER']]);
    }

    function it_configures_options(OptionsResolver $resolver)
    {
        $resolver->setRequired(['roles'])->shouldBeCalled()->willReturn($resolver);
        $resolver->setDefaults(Argument::type('array'))->shouldBeCalled()->willReturn($resolver);

        $this->configureOptions($resolver);
    }
}
