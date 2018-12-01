<?php
/**
 * Created by PhpStorm.
 * User: Ignas
 * Date: 12/1/2018
 * Time: 11:17 AM
 */

namespace App\Controller\TrainerApi;


use App\Entity\AvailabilitySlot;
use App\Entity\User;
use PHP_CodeSniffer\Reports\Json;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use function Symfony\Component\DependencyInjection\Loader\Configurator\expr;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AvailabilitySlotsApiController extends AbstractController
{
    /**
     * @Route("/api/trainer/availability_slot", methods={"POST"})
     * @param Request $request
     * @param null|UserInterface $user
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);

            $user = $this->getUser();

            if (!$user instanceof User) {
                throw new \Exception('User expected');
            }
                $trainer = $user->getTrainer();

                $availabilitySlot = new AvailabilitySlot();

                $availabilitySlot->setStartsAt(new \DateTime($data['starts_at']));
                $availabilitySlot->setEndsAt(new \DateTime($data['ends_at']));
                $availabilitySlot->setTrainer($trainer);
                $this->getDoctrine()->getManager()->persist($availabilitySlot);
                $this->getDoctrine()->getManager()->flush();

                return new JsonResponse($availabilitySlot);

        } catch (\Throwable $exception) {
        }

        return new JsonResponse([], 400);
    }

    /**
     * @Route("/api/trainer/availability_slot")
     */
    public function listAction()
    {
        try {
            $user = $this->getUser();

            if (!$user instanceof User) {
                throw new \Exception('User expected');
            }
                $availabilitySlots = $user->getTrainer()->getAvailabilitySlots()->toArray();
                return new JsonResponse($availabilitySlots);

        } catch (\Exception $e) {

        }

        return new JsonResponse([], 400);



    }

    /**
     * @Route("/api/trainer/availability_slot/{id}", methods={"PUT"})
     */
    public function updateAction(AvailabilitySlot $availabilitySlot, Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);
            var_dump($data);
            die();

            $user = $this->getUser();

            if (!$user instanceof User) {
                throw new \Exception('User expeced');
            }

            if ($user->getTrainer()->getId() !== $availabilitySlot->getTrainer()->getId()) {
                throw new \Exception('Unauthorized');
            }
                $availabilitySlot->setStartsAt(new \DateTime($data['starts_at']));
                $availabilitySlot->setEndsAt(new \DateTime($data['ends_at']));

                $this->getDoctrine()->getManager()->flush();

                return new JsonResponse($availabilitySlot);


        } catch (\Throwable $exception) {

        }

        return new JsonResponse([], 400);

    }

    /**
     * @Route("/api/trainer/availability_slot/{id}", methods={"DELETE"})
     * @param AvailabilitySlot $availabilitySlot
     */
    public function deleteAction(AvailabilitySlot $availabilitySlot)
    {

    }



}