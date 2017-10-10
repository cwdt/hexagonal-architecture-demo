<?php

namespace PTF\Infrastructure\Persistence\InMemory;

use DateTime;
use PTF\Domain\Status\Status;
use PTF\Domain\Status\StatusRepository as StatusRepositoryInterface;
use PTF\Infrastructure\Persistence\Common\StatusRepository as AbstractStatusRepository;

class StatusRepository extends AbstractStatusRepository implements StatusRepositoryInterface
{
    /** @var array */
    private $statuses = [];

    protected function getAllPersistedStatuses(): array
    {
        return $this->statuses;
    }

    protected function persistStatuses(array $statuses): void
    {
        $this->statuses = $statuses;
    }
}