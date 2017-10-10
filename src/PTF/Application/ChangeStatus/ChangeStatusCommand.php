<?php

namespace PTF\Application\ChangeStatus;

use DateTime;

class ChangeStatusCommand
{
    /** @var string */
    private $guid;

    /** @var string */
    private $state;

    /** @var DateTime */
    private $from;

    /** @var DateTime */
    private $until;

    public function __construct(string $guid, string $state, DateTime $from, DateTime $until)
    {
        $this->guid = $guid;
        $this->state = $state;
        $this->from = clone $from;
        $this->until = clone $until;
    }

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getFrom(): DateTime
    {
        return clone $this->from;
    }

    public function getUntil(): DateTime
    {
        return clone $this->until;
    }
}