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

namespace BenGorUser\UserBundle\Security;

use BenGorUser\User\Application\Command\LogIn\LogInUserCommand;
use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\Exception\UserEmailInvalidException;
use BenGorUser\User\Domain\Model\Exception\UserInactiveException;
use BenGorUser\User\Domain\Model\Exception\UserPasswordInvalidException;
use BenGorUser\User\Infrastructure\CommandBus\UserCommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

/**
 * Form login authenticator class.
 *
 * It centralizes all the login process
 * logic around the Symfony security component.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class FormLoginAuthenticator extends AbstractFormLoginAuthenticator
{
    /**
     * The command bus.
     *
     * @var UserCommandBus
     */
    private $commandBus;

    /**
     * The login_check route name.
     *
     * @var string
     */
    private $loginCheckRoute;

    /**
     * The login route name.
     *
     * @var string
     */
    private $loginRoute;

    /**
     * The URL generator.
     *
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * The success redirection route name.
     *
     * @var string
     */
    protected $successRedirectionRoute;

    /**
     * Constructor.
     *
     * @param UrlGeneratorInterface $anUrlGenerator The URL generator
     * @param UserCommandBus        $aCommandBus    The command bus
     * @param array                 $routes         The routes related with security (login, login_check and logout)
     */
    public function __construct(UrlGeneratorInterface $anUrlGenerator, UserCommandBus $aCommandBus, array $routes)
    {
        $this->urlGenerator = $anUrlGenerator;
        $this->commandBus = $aCommandBus;

        if (false === isset(
                $routes['login'],
                $routes['login_check'],
                $routes['success_redirection_route'],
                $routes['success_redirection_route']['type'],
                $routes['success_redirection_route']['route']
            )
        ) {
            throw new \InvalidArgumentException(
                '"routes" array should have "login", "login_check" and "success_redirection_route" ' .
                'keys. Also, "success_redirection_route" should have "type" and "route" keys'
            );
        }
        $this->loginRoute = $routes['login'];
        $this->loginCheckRoute = $routes['login_check'];
        $this->successRedirectionRoute = $routes['success_redirection_route'];
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        if ($this->loginCheckRoute !== $request->attributes->get('_route')) {
            return;
        }
        $email = $request->request->get('_email');
        $request->getSession()->set(Security::LAST_USERNAME, $email);
        $password = $request->request->get('_password');

        return new LogInUserCommand($email, $password);
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $this->commandBus->handle($credentials);
        } catch (UserEmailInvalidException $exception) {
            throw new CustomUserMessageAuthenticationException('security.form_user_not_found_invalid_message');
        } catch (UserInactiveException $exception) {
            throw new CustomUserMessageAuthenticationException('security.form_user_not_found_invalid_message');
        } catch (UserDoesNotExistException $exception) {
            throw new CustomUserMessageAuthenticationException('security.form_user_not_found_invalid_message');
        } catch (UserPasswordInvalidException $exception) {
            throw new CustomUserMessageAuthenticationException('security.form_invalid_credentials_invalid_message');
        }

        return $userProvider->loadUserByUsername($credentials->email());
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => $exception->getMessageKey()], 403);
        }

        return parent::onAuthenticationFailure($request, $exception);
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'user_email' => $token->getUser()->getUsername(),
            ]);
        }

        $targetPath = $request->getSession()->get('_security.' . $providerKey . '.target_path');
        if (!$targetPath || $this->successRedirectionRoute['type'] === 'force') {
            $targetPath = $this->urlGenerator->generate(
                $this->successRedirectionRoute['route'], [], UrlGeneratorInterface::ABSOLUTE_PATH
            );
        }

        return new RedirectResponse($targetPath);
    }

    /**
     * {@inheritdoc}
     */
    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate($this->loginRoute, [], UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultSuccessRedirectUrl()
    {
    }
}
