<?php

namespace PTF\Domain\Status;

use DateTime;

interface StatusRepository
{
    public function save(Status $status): void;

    public function byDateTime(DateTime $dateTime): ?Status;
}