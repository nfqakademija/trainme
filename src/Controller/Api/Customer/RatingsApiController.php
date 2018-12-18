<?php

namespace App\Controller\Api\Customer;

use App\Entity\Rating;
use App\Entity\Trainer;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RatingsApiController extends AbstractController
{
    /**
     * @Route("api/customer/rate/{id}", methods={"POST"})
     * @param Request $request
     * @param Trainer $trainer
     * @return JsonResponse
     */
    public function rateTrainer(Request $request, Trainer $trainer)
    {
        try {
            $customer = $this->getCustomer();

            $alreadyHasARating = $trainer->getRatings()->exists(function ($key, Rating $rating) use ($customer) {
                return $rating->getCustomer()->getId() === $customer->getId();
            });

            if ($alreadyHasARating) {
                throw new \Exception('This trainer is already rated');
            }

            $stars = (int)$request->get('rating');

            $rating = new Rating();
            $rating->setStars($stars);
            $rating->setTrainer($trainer);
            $rating->setCustomer($customer);
            $customer->setHasEvaluatedTrainerOnLogin(true);

            $this->getDoctrine()->getManager()->persist($rating);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse("Success");
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }
}
