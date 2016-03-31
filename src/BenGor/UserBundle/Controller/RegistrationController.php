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

namespace BenGor\UserBundle\Controller;

use BenGor\User\Domain\Model\Exception\UserAlreadyExistException;
use BenGor\UserBundle\Form\Type\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Registration controller.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class RegistrationController extends Controller
{
    /**
     * Register action.
     *
     * @param Request $request      The request
     * @param string  $userClass    Extra parameter that contains the user type
     * @param string  $firewall     Extra parameter that contains the firewall name
     * @param string  $successRoute Extra parameter that contains the success route name
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request, $userClass, $firewall, $successRoute)
    {
        $form = $this->createForm(RegistrationType::class, null, [
            'roles' => $this->getParameter('bengor_user.' . $userClass . '_default_roles'),
        ]);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $service = $this->get('bengor_user.sign_up_' . $userClass);

                try {
                    $response = $service->execute($form->getData());

                    $this
                        ->get('security.authentication.guard_handler')
                        ->authenticateUserAndHandleSuccess(
                            $response->user(),
                            $request,
                            $this->get('bengor_user.form_login_' . $userClass . '_authenticator'),
                            $firewall
                        );
                    $this->addFlash('notice', 'Your changes were saved!');

                    return $this->redirectToRoute($successRoute);
                } catch (UserAlreadyExistException $exception) {
                    $this->addFlash('error', 'The email is already in use.');
                } catch (\Exception $exception) {
                    $this->addFlash('error', $exception->getMessage());
                    $this->addFlash('error', 'An error occurred. Please contact with the administrator.');
                }
            }
        }

        return $this->render('@BenGorUser/registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
