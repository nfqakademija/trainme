<?php

namespace App\Controller;

use App\Services\TrainerEvaluationsService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param TrainerEvaluationsService $trainerEvaluationsService
     * @return Response
     */
    public function home(TrainerEvaluationsService $trainerEvaluationsService)
    {
        try {
            $customer = $this->getCustomer();

            $didEvaluateTrainer = $customer->getHasEvaluatedTrainerOnLogin();

            if ($didEvaluateTrainer) {
                throw  new \Exception('User already rated a trainer.');
            }

            $trainerToEvaluate = $trainerEvaluationsService->getTrainerForEvaluation($customer);

        } catch (\Exception $e) {
            $trainerToEvaluate = null;
        }

        return $this->render('home/home.html.twig', ['trainerToEvaluate' => $trainerToEvaluate]);
    }
}
