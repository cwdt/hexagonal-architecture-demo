<?php

namespace PTF\Domain\Status;

use DateTime;

class Status
{
    /** @var string */
    private $guid;

    /** @var State */
    private $state;

    /** @var DateTime */
    private $from;

    /** @var DateTime */
    private $until;

    /** @var DateTime */
    private $createdAt;

    private function __construct(string $guid, State $state, DateTime $from, DateTime $until, DateTime $createdAt)
    {
        $this->guid = $guid;
        $this->state = $state;
        $this->from = $from;
        $this->until = $until;
        $this->createdAt = $createdAt;
    }

    public static function create(string $guid, State $state, DateTime $from, DateTime $until): Status
    {
        if ($from->format('Y-m-d') !== $until->format('Y-m-d')) {
            throw new InvalidPeriodException('The until date can not exceed the current date');
        }

        return new Status(
            $guid,
            $state,
            $from,
            $until,
            new DateTime()
        );
    }

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function getState(): State
    {
        return $this->state;
    }

    public function getFrom(): DateTime
    {
        return $this->from;
    }

    public function getUntil(): DateTime
    {
        return $this->until;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}