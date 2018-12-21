<?php

namespace App\Exceptions;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends \Exception
{
    private $errors;

    public function __construct(
        string $message = "",
        ConstraintViolationListInterface $errors = null
    ) {
        $this->errors = $errors;
        parent::__construct($message);
    }

    public function getErrorsArray()
    {
        $array = [];

        foreach ($this->errors as $error) {
            $array[] = $error->getMessage();
        }

        return $array;
    }
}
