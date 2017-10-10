<?php

namespace PTF\Application\GetState;

use DateTime;

class GetStateQuery
{
    /** @var DateTime */
    private $dateTime;

    public function __construct(DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }
}