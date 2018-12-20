<?php

namespace App\Controller\Api\Customer;

use App\Controller\AbstractController;
use App\Entity\ScheduledWorkout;
use App\Exceptions\ValidationException;
use App\Repository\TrainerRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ScheduledWorkoutsApiController extends AbstractController
{
    /**
     * @Route("/api/scheduled_workout", methods={"POST"})
     * @param Request $request
     * @param TrainerRepository $trainerRepository
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function createWorkout(
        Request $request,
        TrainerRepository $trainerRepository,
        ValidatorInterface $validator
    ) {
        try {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['starts_at']) || !isset($data['ends_at']) || !isset($data['trainer_id'])) {
                throw new \Exception('Parameters \'starts_at\' or \'ends_at\' or \'trainer_id\' are not defined');
            }


            $trainer = $trainerRepository->find($data['trainer_id']);

            $customer = $this->getCustomer();

            if (!$trainer) {
                throw new \Exception('Trainer not found');
            }

            $scheduledWorkout = new ScheduledWorkout();

            $scheduledWorkout->setStartsAt(new \DateTime($data['starts_at']));
            $scheduledWorkout->setEndsAt(new \DateTime($data['ends_at']));
            $scheduledWorkout->setTrainer($trainer);
            $scheduledWorkout->setCustomer($customer);

            $errors = $validator->validate($scheduledWorkout);

            if (count($errors) > 0) {
                throw new ValidationException('Validation error', $errors);
            }

            $this->getDoctrine()->getManager()->persist($scheduledWorkout);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse($scheduledWorkout);
        } catch (ValidationException $exception) {
            return new JsonResponse(['validationError' => true,'errors' => $exception->getErrorsArray()], 400);
        } catch (\Exception $exception) {
            return new JsonResponse(['errors' => [$exception->getMessage()]]);
        }
    }

    /**
     * @Route("/api/scheduled_workout", methods={"GET"})
     */
    public function listWorkouts()
    {
        try {
            $customer = $this->getCustomer();

            $scheduledWorkouts = $customer->getScheduledWorkouts()->toArray();

            return new JsonResponse($scheduledWorkouts);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }

    /**
     * @Route("/api/scheduled_workout/{id}", methods={"DELETE"})
     * @param ScheduledWorkout $scheduledWorkout
     * @return JsonResponse
     */
    public function deleteWorkout(ScheduledWorkout $scheduledWorkout)
    {
        try {
            if (!$scheduledWorkout) {
                throw new \Exception('No scheduled workout found');
            }

            $customer = $this->getCustomer();

            if ($customer->getId() !== $scheduledWorkout->getCustomer()->getId()) {
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
