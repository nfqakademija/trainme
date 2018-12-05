<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Trainer;
use App\Entity\User;
use App\Form\CustomerRegistrationType;
use App\Form\TrainerRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @return Response
     */
    public function customerRegister(Request $request, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $form = $this->createForm(CustomerRegistrationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $data = $form->getData();

            /** @var Customer $customer */
            $customer = $data['personal_info'];

            $user = new User();

            $user->setEmail($data['email']);
            $user->setPassword($userPasswordEncoder->encodePassword($user, $data['password']));
            $user->setRoles(['ROLE_CUSTOMER']);

            $em->persist($user);

            $customer->setUser($user);

            $em->persist($customer);

            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/customer/register.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/trainer/register", name="app_trainer_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @return Response
     */
    public function trainerRegister(Request $request, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $form = $this->createForm(TrainerRegistrationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $data = $form->getData();

            /** @var Trainer $trainer */
            $trainer = $data['personal_info'];

            $user = new User();

            $user->setEmail($data['email']);
            $user->setPassword($userPasswordEncoder->encodePassword($user, $data['password']));
            $user->setRoles(['ROLE_TRAINER']);

            $em->persist($user);

            $trainer->setUser($user);

            $em->persist($trainer);

            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/trainer/register.html.twig', ['form' => $form->createView()]);
    }
}
