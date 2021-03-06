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

namespace spec\BenGorUser\UserBundle\Security;

use BenGorUser\User\Application\Command\LogIn\LogInUserCommand;
use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\Exception\UserEmailInvalidException;
use BenGorUser\User\Domain\Model\Exception\UserInactiveException;
use BenGorUser\User\Domain\Model\Exception\UserPasswordInvalidException;
use BenGorUser\User\Infrastructure\CommandBus\UserCommandBus;
use BenGorUser\UserBundle\Security\FormLoginAuthenticator;
use BenGorUser\UserBundle\Security\User;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

/**
 * Spec file of FormLoginAuthenticator class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class FormLoginAuthenticatorSpec extends ObjectBehavior
{
    function let(UrlGeneratorInterface $urlGenerator, UserCommandBus $commandBus)
    {
        $this->beConstructedWith($urlGenerator, $commandBus, [
            'login'                     => 'bengor_user_user_security_login',
            'login_check'               => 'bengor_user_user_security_login_check',
            'success_redirection_route' => [
                'type'  => 'referer',
                'route' => 'bengor_user_user_security_homepage',
            ],
        ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FormLoginAuthenticator::class);
    }

    function it_extends_abstract_form_login_authenticator()
    {
        $this->shouldHaveType(AbstractFormLoginAuthenticator::class);
    }

    function it_throws_invalid_argument_exception_when_routes_are_not_provided(
        UrlGeneratorInterface $urlGenerator,
        UserCommandBus $commandBus
    ) {
        $this->beConstructedWith($urlGenerator, $commandBus, []);

        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    function it_gets_credentials(
        Request $request,
        ParameterBagInterface $parameterBag,
        ParameterBagInterface $attributesBag,
        SessionInterface $session
    ) {
        $parameterBag->get('_email')->shouldBeCalled()->willReturn('test@test.com');
        $parameterBag->get('_password')->shouldBeCalled()->willReturn('111111');
        $attributesBag->get('_route')->shouldBeCalled()->willReturn(
            'bengor_user_user_security_login_check'
        );
        $request->request = $parameterBag;
        $request->attributes = $attributesBag;

        $request->getSession()->shouldBeCalled()->willReturn($session);
        $session->set(Security::LAST_USERNAME, 'test@test.com')->shouldBeCalled();

        $this->getCredentials($request)->shouldReturnAnInstanceOf(LogInUserCommand::class);
    }

    function it_on_authentication_failure_when_is_xml_http_request(Request $request, AuthenticationException $exception)
    {
        $request->isXmlHttpRequest()->shouldBeCalled()->willReturn(true);

        $this->onAuthenticationFailure($request, $exception)->shouldReturnAnInstanceOf(JsonResponse::class);
    }

    function it_on_authentication_success_when_is_xml_http_request(
        Request $request,
        TokenInterface $token,
        User $user
    ) {
        $request->isXmlHttpRequest()->shouldBeCalled()->willReturn(true);
        $token->getUser()->shouldBeCalled()->willReturn($user);
        $user->getUsername()->shouldBeCalled()->willReturn('bengor@user.com');

        $this->onAuthenticationSuccess($request, $token, 'main')->shouldReturnAnInstanceOf(JsonResponse::class);
    }

    function it_gets_user(
        UserProviderInterface $userProvider,
        UserCommandBus $commandBus,
        LogInUserCommand $credentials,
        User $user
    ) {
        $commandBus->handle($credentials)->shouldBeCalled();
        $credentials->email()->shouldBeCalled()->willReturn('bengor@user.com');
        $userProvider->loadUserByUsername('bengor@user.com')->shouldBeCalled()->willReturn($user);

        $this->getUser($credentials, $userProvider)->shouldReturn($user);
    }

    function it_does_not_get_user_because_user_does_not_exist(
        UserProviderInterface $userProvider,
        UserCommandBus $commandBus,
        LogInUserCommand $credentials
    ) {
        $commandBus->handle($credentials)->shouldBeCalled()->willThrow(UserDoesNotExistException::class);

        $this->shouldThrow(
            new CustomUserMessageAuthenticationException('security.form_user_not_found_invalid_message')
        )->duringGetUser($credentials, $userProvider);
    }

    function it_does_not_get_user_because_user_is_inactive(
        UserProviderInterface $userProvider,
        UserCommandBus $commandBus,
        LogInUserCommand $credentials
    ) {
        $commandBus->handle($credentials)->shouldBeCalled()->willThrow(UserInactiveException::class);

        $this->shouldThrow(
            new CustomUserMessageAuthenticationException('security.form_user_not_found_invalid_message')
        )->duringGetUser($credentials, $userProvider);
    }

    function it_does_not_get_user_because_password_is_invalid(
        UserProviderInterface $userProvider,
        UserCommandBus $commandBus,
        LogInUserCommand $credentials
    ) {
        $commandBus->handle($credentials)->shouldBeCalled()->willThrow(UserPasswordInvalidException::class);

        $this->shouldThrow(
            new CustomUserMessageAuthenticationException('security.form_invalid_credentials_invalid_message')
        )->duringGetUser($credentials, $userProvider);
    }

    function it_does_not_get_user_because_email_is_invalid(
        UserProviderInterface $userProvider,
        UserCommandBus $commandBus,
        LogInUserCommand $credentials
    ) {
        $commandBus->handle($credentials)->shouldBeCalled()->willThrow(UserEmailInvalidException::class);

        $this->shouldThrow(
            new CustomUserMessageAuthenticationException('security.form_user_not_found_invalid_message')
        )->duringGetUser($credentials, $userProvider);
    }

    function it_checks_credentials(LogInUserCommand $credentials, User $user)
    {
        $this->checkCredentials($credentials, $user)->shouldReturn(true);
    }
}
