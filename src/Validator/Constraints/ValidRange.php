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
    public $message = 'This date range is invalid.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
