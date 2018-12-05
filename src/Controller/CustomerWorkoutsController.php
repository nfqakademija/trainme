<?php

namespace App\Controller;

use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomerWorkoutsController extends AbstractController
{
    /**
     * @Route("/workouts", name="customer_workouts")
     */
    public function index()
    {
        return $this->render('customer/workouts.html.twig');
    }
}
