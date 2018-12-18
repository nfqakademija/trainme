<?php
/**
 * Created by PhpStorm.
 * User: Ignas
 * Date: 12/18/2018
 * Time: 2:23 PM
 */

namespace App\Validator\Constraints;

use App\Entity\ScheduledWorkout;
use App\Services\AvailableTimesCalculationService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ScheduledWorkoutInAvailableTimesValidator extends ConstraintValidator
{
    private $availableTimesCalculationService;

    public function __construct(AvailableTimesCalculationService $availableTimesCalculationService)
    {
        $this->availableTimesCalculationService = $availableTimesCalculationService;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ScheduledWorkoutInAvailableTimes) {
            throw new UnexpectedTypeException($constraint, ScheduledWorkoutInAvailableTimes::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof ScheduledWorkout) {
            throw new UnexpectedTypeException($value, ScheduledWorkout::class);
        }

        $trainerAvailableTimes = $this->availableTimesCalculationService->getAvailableTimes($value->getTrainer());

        $availableTimeExists = false;
        foreach ($trainerAvailableTimes as $availableTime) {
            if ($availableTime->getStartsAt() <= $value->getStartsAt()
                && $availableTime->getEndsAt() >= $value->getEndsAt()) {
                $availableTimeExists = true;
                break;
            }
        }

        if (!$availableTimeExists) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
