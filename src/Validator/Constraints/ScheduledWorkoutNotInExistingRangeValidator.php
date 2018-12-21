<?php
/**
 * Created by PhpStorm.
 * User: Ignas
 * Date: 12/20/2018
 * Time: 11:42 PM
 */

namespace App\Validator\Constraints;

use App\Entity\ScheduledWorkout;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ScheduledWorkoutNotInExistingRangeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ScheduledWorkoutNotInExistingRange) {
            throw new UnexpectedTypeException($constraint, ScheduledWorkoutNotInExistingRange::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof ScheduledWorkout) {
            throw new UnexpectedTypeException($value, ScheduledWorkout::class);
        }

        $customer = $value->getCustomer();

        foreach ($customer->getScheduledWorkouts() as $scheduledWorkout) {
            if ($value->getId() === $scheduledWorkout->getId()) {
                continue;
            }

            if (($value->getStartsAt() >= $scheduledWorkout->getStartsAt()
                && $value->getStartsAt() < $scheduledWorkout->getEndsAt())
                || ($value->getEndsAt() > $scheduledWorkout->getStartsAt()
                && $value->getEndsAt() < $scheduledWorkout->getEndsAt())
            ) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
                break;
            }
        }
    }
}
