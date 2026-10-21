<?php

namespace App\Exceptions;

use Illuminate\Support\MessageBag;
use RuntimeException;

class EnrollmentValidationException extends RuntimeException
{
    protected MessageBag $errors;

    public function __construct(MessageBag $errors)
    {
        parent::__construct($errors->first());
        $this->errors = $errors;
    }

    public function getErrors(): MessageBag
    {
        return $this->errors;
    }
}
