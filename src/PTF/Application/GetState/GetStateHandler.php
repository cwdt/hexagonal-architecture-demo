<?php

namespace PTF\Application\GetState;

use PTF\Domain\Daytime\DaytimeRepository;
use PTF\Domain\Status\State;
use PTF\Domain\Status\StatusRepository;

class GetStateHandler
{
    /** @var DaytimeRepository */
    private $dayTimeRepository;

    /** @var StatusRepository */
    private $statusRepository;

    public function __construct(DaytimeRepository $dayTimeRepository, StatusRepository $statusRepository)
    {
        $this->dayTimeRepository = $dayTimeRepository;
        $this->statusRepository = $statusRepository;
    }

    public function handle(GetStateQuery $query): GetStateResponse
    {
        $dateTime = $query->getDateTime();
        $status = $this->statusRepository->byDateTime($dateTime);
        if ($status) {
            return new GetStateResponse((string)$status->getState());
        }

        $currentDaytime = $this->dayTimeRepository->byDate($dateTime);
        if ($dateTime < $currentDaytime->getSunrise() || $dateTime > $currentDaytime->getSunset()) {

            return new GetStateResponse(State::FORBIDDEN);
        }

        return new GetStateResponse(State::UNKNOWN);
    }
}