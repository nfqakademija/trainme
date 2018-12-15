<?php

namespace App\Controller\Api\Trainer;

use App\Entity\AvailabilitySlot;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AvailabilitySlotsApiController extends AbstractController
{
    /**
     * @Route("/api/availability_slot", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function createAction(Request $request, ValidatorInterface $validator)
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['starts_at']) || !isset($data['ends_at'])) {
                throw new \Exception('Parameters \'starts_at\' or/and \'ends_at\' are not defined');
            }

            $user = $this->getUser();

            if (!$user instanceof User) {
                throw new \Exception('User expected');
            }
            $trainer = $user->getTrainer();

            $availabilitySlot = new AvailabilitySlot();

            $availabilitySlot->setStartsAt(new \DateTime($data['starts_at']));
            $availabilitySlot->setEndsAt(new \DateTime($data['ends_at']));
            $availabilitySlot->setTrainer($trainer);

            $errors = $validator->validate($availabilitySlot);

            if (count($errors) > 0) {
                throw new \Exception('Validation error');
            }

            foreach ($trainer->getAvailabilitySlots() as $existingAvailabilitySlot) {
                if (($existingAvailabilitySlot->getStartsAt() > $availabilitySlot->getStartsAt()
                        && $existingAvailabilitySlot->getStartsAt() < $availabilitySlot->getEndsAt())
                    || ($existingAvailabilitySlot->getEndsAt() > $availabilitySlot->getStartsAt()
                        && $existingAvailabilitySlot->getEndsAt() < $availabilitySlot->getEndsAt())) {
                    throw new \Exception('Availability slot already exists in this range');
                }
            }

            $this->getDoctrine()->getManager()->persist($availabilitySlot);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse($availabilitySlot);
        } catch (\Throwable $exception) {
            return new JsonResponse($exception->getMessage(), 400);
        }
    }

    /**
     * @Route("/api/availability_slot")
     */
    public function listAction()
    {
        try {
            $user = $this->getUser();

            if (!$user instanceof User) {
                throw new \Exception('User expected');
            }
            $availabilitySlots = $user->getTrainer()->getAvailabilitySlots()->getIterator();

            return new JsonResponse($availabilitySlots);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }

    /**
     * @Route("/api/availability_slot/{id}", methods={"PUT"})
     * @param AvailabilitySlot $availabilitySlot
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function updateAction(AvailabilitySlot $availabilitySlot, Request $request, ValidatorInterface $validator)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $user = $this->getUser();

            if (!$availabilitySlot) {
                throw new \Exception('No availability slot found');
            }

            if (!$user instanceof User) {
                throw new \Exception('User expected');
            }

            if ($user->getTrainer()->getId() !== $availabilitySlot->getTrainer()->getId()) {
                throw new \Exception('Unauthorized');
            }

            if (isset($data['starts_at'])) {
                $availabilitySlot->setStartsAt(new \DateTime($data['starts_at']));
            }
            if (isset($data['ends_at'])) {
                $availabilitySlot->setEndsAt(new \DateTime($data['ends_at']));
            }

            $errors = $validator->validate($availabilitySlot);

            if (count($errors) > 0) {
                throw new \Exception('Validation error');
            }

            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse($availabilitySlot);
        } catch (\Throwable $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }

    /**
     * @Route("/api/availability_slot/{id}", methods={"DELETE"})
     * @param AvailabilitySlot $availabilitySlot
     * @return JsonResponse
     */
    public function deleteAction(AvailabilitySlot $availabilitySlot)
    {
        try {
            $user = $this->getUser();

            if (!$availabilitySlot) {
                throw new \Exception('No availability slot found');
            }

            if (!$user instanceof User) {
                throw new \Exception('User expected');
            }

            if ($user->getTrainer()->getId() !== $availabilitySlot->getTrainer()->getId()) {
                throw new \Exception('Unauthorized');
            }

            $this->getDoctrine()->getManager()->remove($availabilitySlot);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse('SUCCESS');
        } catch (\Throwable $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }
}
