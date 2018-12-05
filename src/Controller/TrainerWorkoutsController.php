<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TrainerWorkoutsController extends AbstractController
{
    /**
     * @Route("/trainer/workouts", name="trainer_workouts")
     */
    public function index()
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('trainer/workouts.html.twig');
    }
}
