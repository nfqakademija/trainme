<?php

namespace App\Services;

use App\Entity\Customer;
use App\Entity\Trainer;
use App\Repository\TrainerRepository;

class TrainerEvaluationsService
{
    private $trainerRepository;

    public function __construct(TrainerRepository $trainerRepository)
    {
        $this->trainerRepository = $trainerRepository;
    }

    /**
     * @param Customer $customer
     * @return Trainer
     */
    public function getTrainerForEvaluation(Customer $customer)
    {
        $trainers = $this->trainerRepository->getNotEvaluatedTrainers($customer);

        if (count($trainers) == 0) {
            return null;
        }

        return $trainers[0];
    }
}
