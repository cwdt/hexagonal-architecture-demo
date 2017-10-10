<?php

namespace PTF\Domain\Daytime;

use DateTime;

interface DaytimeRepository
{
    public function byDate(DateTime $date): Daytime;
}