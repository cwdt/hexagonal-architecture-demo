<?php

namespace PTF\Domain\Daytime;

use BadMethodCallException;

class EndExceedsStartException extends BadMethodCallException
{
    public static function bySunriseAndSunset(\DateTime $start, \DateTime $end): EndExceedsStartException
    {
        return new self(
            sprintf(
                'Start %s exceeds end %s',
                $start->format(DATE_ATOM),
                $end->format(DATE_ATOM)
            )
        );
    }
}