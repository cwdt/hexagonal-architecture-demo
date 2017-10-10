<?php

namespace PTF\Application\GetState;

use JsonSerializable;

class GetStateResponse implements JsonSerializable
{
    /** @var string */
    private $state;

    /**
     * @param string $state
     */
    public function __construct($state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    public function jsonSerialize(): array
    {
        return [
            'state' => $this->getState(),
        ];
    }
}