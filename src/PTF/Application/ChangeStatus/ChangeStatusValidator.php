<?php

namespace PTF\Application\ChangeStatus;

use Ptf\Application\ValidationException;
use PTF\Domain\Daytime\DaytimeRepository;
use PTF\Domain\Status\State;

class ChangeStatusValidator
{
    /** @var DaytimeRepository */
    private $daytimeRepository;

    public function __construct(DaytimeRepository $daytimeRepository)
    {
        $this->daytimeRepository = $daytimeRepository;
    }

    public function validate(ChangeStatusCommand $command): bool
    {
        $errors = [];

        if (! in_array($command->getState(), State::$states)) {
            $errors['state'][] = 'invalid';
        }

        if ($command->getFrom()->format('Y-m-d') !== $command->getUntil()->format('Y-m-d')) {
            $errors['from'][] = 'diffent_days';
        }

        if ($command->getFrom() > $command->getUntil()) {
            $errors['from'][] = 'exceeds';
        }

        $daytime = $this->daytimeRepository->byDate($command->getFrom());
        if ($daytime->getSunrise() > $command->getFrom()) {
            $errors['from'][] = 'sunrise';
        }

        if ($daytime->getSunset() < $command->getUntil()) {
            $errors['until'][] = 'sunset';
        }

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        return true;
    }
}
