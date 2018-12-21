<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CustomerRegistrationType;
use App\Form\TrainerRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
    }

    /**
     * @Route("/customer/register", name="app_customer_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function customerRegister(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $form = $this->createForm(CustomerRegistrationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $customer = $form->getData();
            $password = $passwordEncoder->encodePassword(
                $customer->getUser(),
                $customer->getUser()->getPlainPassword()
            );
            $customer->getUser()->setPassword($password);
            $customer->getUser()->setRoles([User::ROLE_CUSTOMER]);
            $em->persist($customer);
            $em->flush();

            $this->addFlash('success', 'You registered successfully. You can now login!');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/customer/register.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/trainer/register", name="app_trainer_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function trainerRegister(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $form = $this->createForm(TrainerRegistrationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $trainer = $form->getData();
            $password = $passwordEncoder->encodePassword(
                $trainer->getUser(),
                $trainer->getUser()->getPlainPassword()
            );
            $trainer->getUser()->setPassword($password);
            $trainer->getUser()->setRoles([User::ROLE_TRAINER]);
            $em->persist($trainer);
            $em->flush();

            $this->addFlash('success', 'You registered successfully. You can now login!');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/trainer/register.html.twig', ['form' => $form->createView()]);
    }
}
