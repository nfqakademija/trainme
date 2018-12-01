<?php

namespace App\Controller;

use App\Entity\Trainer;
use App\Services\AvailableTimesCalculationService;
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

    /**
     * @Route("/api/trainers/{id}/available_times, name="api_available_times")
     * @param Trainer $trainer
     * @param AvailableTimesCalculationService $availableTimesCalculationService
     * @return JsonResponse
     */
    public function getAvailableTimes(Trainer $trainer, AvailableTimesCalculationService $availableTimesCalculationService)
    {
        return new JsonResponse($availableTimesCalculationService->getAvailableTimes($trainer));
    }
}
