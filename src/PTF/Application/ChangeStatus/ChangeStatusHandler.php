<?php

namespace PTF\Application\ChangeStatus;

use PTF\Domain\Status\State;
use PTF\Domain\Status\Status;
use PTF\Domain\Status\StatusRepository;

class ChangeStatusHandler
{
    /** @var StatusRepository */
    private $statusRepository;

    public function __construct(StatusRepository $statusRepository)
    {
        $this->statusRepository = $statusRepository;
    }

    public function handle(ChangeStatusCommand $command): void
    {
        $status = Status::create(
            $command->getGuid(),
            State::create($command->getState()),
            $command->getFrom(),
            $command->getUntil()
        );

        $this->statusRepository->save($status);
    }
}