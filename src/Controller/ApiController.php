<?php

namespace App\Controller;

use App\Entity\Trainer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @Route("/api/trainers/{id}", name="api_trainer", methods={"PUT"})
     * @param Trainer $trainer
     * @param Request $request
     * @return JsonResponse
     */
    public function updateTrainerPersonalStatement(Request $request, ?UserInterface $user, Trainer $trainer)
    {
        if (!$user) {
            return;
        }


        $em = $this->getDoctrine()->getManager();

        $data = $request->getContent();
        $data = json_decode($data, true);


        $trainer->setPersonalStatement($data['personal_statement']);
        $em->flush();


        return new JsonResponse($data);
    }
}
