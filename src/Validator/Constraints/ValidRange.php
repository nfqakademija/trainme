<?php
/**
 * Created by PhpStorm.
 * User: Ignas
 * Date: 12/20/2018
 * Time: 4:01 PM
 */

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ValidRange extends Constraint
{
    public $message = 'Provided date range cannot be in the past.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
