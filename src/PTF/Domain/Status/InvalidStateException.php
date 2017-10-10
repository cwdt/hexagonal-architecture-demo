<?php

namespace PTF\Domain\Status;

use BadMethodCallException;

class InvalidStateException extends BadMethodCallException
{
    public static function byState(string $given): InvalidStateException
    {
        return new self(
            sprintf(
                'Invalid state %s given (valid states are: %s)',
                $given,
                implode(State::$states, ', ')
            )
        );
    }
}