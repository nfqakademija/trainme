<?php

namespace App\Services;

use App\Entity\Customer;
use App\Entity\Trainer;
use App\Repository\TrainerRepository;

/**
 * Class TrainerEvaluationsService
 * @package App\Services
 */
class TrainerEvaluationsService
{
    /**
     * @var TrainerRepository
     */
    private $trainerRepository;

    /**
     * TrainerEvaluationsService constructor.
     * @param TrainerRepository $trainerRepository
     */
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
