<?php

namespace App\Controller\Api\Trainer;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrainerApiController extends AbstractController
{
    /**
     * @Route("/api/trainer", methods={"PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function updateAction(Request $request)
    {
        try {
            $user = $this->getUser();

            $data = json_decode($request->getContent(), true);

            if (!$user instanceof User) {
                throw new \Exception('User expected');
            }

            $trainer = $user->getTrainer();

            if (isset($data['personal_statement'])) {
                $trainer->setPersonalStatement($data['personal_statement']);
            }

            if (isset($data['phone'])) {
                $trainer->setPhone($data['phone']);
            }

            if (isset($data['location'])) {
                $trainer->setLocation($data['location']);
            }

            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse($trainer);
        } catch (\Throwable $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }
}
