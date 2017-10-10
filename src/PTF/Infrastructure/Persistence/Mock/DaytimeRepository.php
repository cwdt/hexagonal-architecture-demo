<?php

namespace PTF\Infrastructure\Persistence\Mock;

use DateTime;
use PTF\Domain\Daytime\Daytime;
use PTF\Domain\Daytime\DaytimeRepository as DaytimeRepositoryInterface;

class DaytimeRepository implements DaytimeRepositoryInterface
{
    protected $startHour = 7;
    protected $endHour = 21;

    public function setDaytime($startHour, $endHour): void
    {
        $this->startHour = $startHour;
        $this->endHour = $endHour;
    }

    public function byDate(DateTime $date): Daytime
    {
        $start = clone $date;
        $start->setTime($this->startHour, 0, 0, 0);
        $end = clone $date;
        $end->setTime($this->endHour, 0, 0, 0);

        return Daytime::create($start, $end);
    }
}