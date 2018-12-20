<?php
/**
 * Created by PhpStorm.
 * User: Ignas
 * Date: 12/18/2018
 * Time: 2:20 PM
 */

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ScheduledWorkoutInAvailableTimes extends Constraint
{
    public $message = 'Trainer does not have available time for this period';

    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
