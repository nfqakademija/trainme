<?php
/**
 * Created by PhpStorm.
 * User: Ignas
 * Date: 12/3/2018
 * Time: 4:15 PM
 */

namespace App\Controller\Api\Client;

use App\Repository\TrainerRepository;
use App\Services\AvailableTimesCalculationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ScheduledWorkout;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ScheduledWorkoutsApiController extends AbstractController
{
    /**
     * @Route("/api/scheduled_workout", methods={"POST"})
     * @param Request $request
     * @param TrainerRepository $trainerRepository
     * @param AvailableTimesCalculationService $availableTimesCalculationService
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function createAction(Request $request, TrainerRepository $trainerRepository, AvailableTimesCalculationService $availableTimesCalculationService, ValidatorInterface $validator)
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['starts_at']) || !isset($data['ends_at']) || !isset($data['trainer_id'])) {
                throw new \Exception('Parameters \'starts_at\' or/and \'ends_at\' or/and \'trainer_id\' are not defined');
            }

            $user = $this->getUser();

            $trainer = $trainerRepository->find($data['trainer_id']);

            if (!$user instanceof User) {
                throw new \Exception('User expected');
            }

            if (!$trainer) {
                throw new \Exception('Trainer not found');
            }

            $scheduledWorkout = new ScheduledWorkout();

            $scheduledWorkout->setStartsAt(new \DateTime($data['starts_at']));
            $scheduledWorkout->setEndsAt(new \DateTime($data['ends_at']));
            $scheduledWorkout->setTrainer($trainer);
            $scheduledWorkout->setUser($user);

            $errors = $validator->validate($scheduledWorkout);

            if (count($errors) > 0) {
                throw new \Exception('Validation error');
            }

            $trainerAvailableTimes = $availableTimesCalculationService->getAvailableTimes($trainer);
            
            $availableTimeExists = false;
            foreach ($trainerAvailableTimes as $availableTime) {
                if ($availableTime->getStartsAt() <= $scheduledWorkout->getStartsAt() && $availableTime->getEndsAt() >= $scheduledWorkout->getEndsAt()){
                    $availableTimeExists = true;
                    break;
                }
            }

            if (!$availableTimeExists) {
                throw new \Exception('Trainer does not have available time for this period');
            }

            $this->getDoctrine()->getManager()->persist($scheduledWorkout);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse($scheduledWorkout);

        } catch (\Throwable $exception) {
            return new JsonResponse($exception->getMessage(), 400);
        }
    }

    /**
     * @Route("/api/scheduled_workout", methods={"GET"})
     */
    public function listAction()
    {
        try {
            $user = $this->getUser();

            if (!$user instanceof User) {
                throw new \Exception('User expected');
            }
            $scheduledWorkouts = $user->getScheduledWorkouts();

            return new JsonResponse($scheduledWorkouts);

        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }


    }

    /**
     * @Route("/api/scheduled_workout/{id}", methods={"PUT"})
     * @param ScheduledWorkout $scheduledWorkout
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function updateAction(ScheduledWorkout $scheduledWorkout, Request $request, ValidatorInterface $validator)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $user = $this->getUser();

            if (!$scheduledWorkout) {
                throw new \Exception('No scheduled workout found');
            }

            if (!$user instanceof User) {
                throw new \Exception('User expected');
            }

            if ($user->getId() !== $scheduledWorkout->getUser()->getId()) {
                throw new \Exception('Unauthorized');
            }

            if (isset($data['starts_at'])) {
                $scheduledWorkout->setStartsAt(new \DateTime($data['starts_at']));
            }
            if (isset($data['ends_at'])) {
                $scheduledWorkout->setEndsAt(new \DateTime($data['ends_at']));
            }

            $errors = $validator->validate($scheduledWorkout);

            if (count($errors) > 0) {
                throw new \Exception('Validation error');
            }

            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse($scheduledWorkout);


        } catch (\Throwable $e) {
            return new JsonResponse($e->getMessage(), 400);
        }



    }

    /**
     * @Route("/api/scheduled_workout/{id}", methods={"DELETE"})
     * @param ScheduledWorkout $scheduledWorkout
     * @return JsonResponse
     */
    public function deleteAction(ScheduledWorkout $scheduledWorkout)
    {
        try {
            $user = $this->getUser();

            if (!$scheduledWorkout) {
                throw new \Exception('No scheduled workout found');
            }

            if (!$user instanceof User) {
                throw new \Exception('User expected');
            }

            if ($user->getId() !== $scheduledWorkout->getUser()->getId()) {
                throw new \Exception('Unauthorized');
            }

            $this->getDoctrine()->getManager()->remove($scheduledWorkout);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse('SUCCESS');


        } catch (\Throwable $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }


}