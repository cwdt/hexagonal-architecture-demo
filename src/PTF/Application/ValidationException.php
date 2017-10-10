<?php

namespace Ptf\Application;

use Exception;

class ValidationException extends Exception
{
    /** @var array */
    private $errors;

    /**
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}