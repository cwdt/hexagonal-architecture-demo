<?php

namespace PTF\Domain\Daytime;

use DateTime;

class Daytime
{
    /** @var DateTime */
    private $sunrise;

    /** @var DateTime */
    private $sunset;

    private function __construct(DateTime $sunrise, DateTime $sunset)
    {
        $this->sunrise = clone $sunrise;
        $this->sunset = clone $sunset;
    }

    public static function create(DateTime $sunrise, DateTime $sunset): Daytime
    {
        if ($sunrise > $sunset) {
            throw EndExceedsStartException::bySunriseAndSunset($sunrise, $sunset);
        }

        return new self(
            $sunrise,
            $sunset
        );
    }

    public function getSunrise(): DateTime
    {
        return clone $this->sunrise;
    }

    public function getSunset(): DateTime
    {
        return clone $this->sunset;
    }
}