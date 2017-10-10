<?php

namespace PTF\Domain\Status;

class State
{
    const ALLOWED = 'allowed';
    const FORBIDDEN = 'forbidden';
    const UNKNOWN = 'unknown';

    /** @var array */
    public static $states = [
        self::ALLOWED,
        self::FORBIDDEN,
        self::UNKNOWN,
    ];

    /** @var string */
    private $state;

    public static function create(string $state): State
    {
        if (! in_array($state, self::$states)) {
            throw InvalidStateException::byState($state);
        }
        $input = $state;
        $state = new State();
        $state->state = $input;
        return $state;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function __toString(): string
    {
        return $this->getState();
    }
}
