<?php
/**
 * Created by PhpStorm.
 * User: Ignas
 * Date: 12/20/2018
 * Time: 11:42 PM
 */

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ScheduledWorkoutNotInExistingRange extends Constraint
{
    public $message = 'You already booked a workout within this range.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
