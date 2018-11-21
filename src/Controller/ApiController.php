<?php

namespace App\Controller;

use App\Entity\Trainer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/trainers/{id}/scheduledWorkouts", name="api_scheduled_workouts")
     * @param Trainer $trainer
     * @return JsonResponse
     */
    public function getScheduledWorkouts(Trainer $trainer)
    {
        return new JsonResponse($trainer->getScheduledWorkouts()->toArray());
    }

    /**
     * @Route("/api/trainers/{id}/availabilitySlots", name="api_availability_slots")
     * @param Trainer $trainer
     * @return JsonResponse
     */
    public function getAvailabilitySlots(Trainer $trainer)
    {
        return new JsonResponse($trainer->getAvailabilitySlots()->toArray());
    }
}
