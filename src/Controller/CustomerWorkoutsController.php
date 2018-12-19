<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
