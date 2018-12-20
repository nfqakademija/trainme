<?php
/**
 * Created by PhpStorm.
 * User: Ignas
 * Date: 12/20/2018
 * Time: 4:10 PM
 */

namespace App\Validator\Constraints;

use App\Interfaces\DateRangeInterface;
use App\Services\AvailableTimesCalculationService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidRangeValidator extends ConstraintValidator
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
        if (!$constraint instanceof ValidRange) {
            throw new UnexpectedTypeException($constraint, ValidRange::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof DateRangeInterface) {
            throw new UnexpectedTypeException($value, DateRangeInterface::class);
        }

        $currentDate = new \DateTime();
        if ($value->getStartsAt() >= $value->getEndsAt()
            || $value->getStartsAt() <= $currentDate
            || $value->getEndsAt() <= $currentDate) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
