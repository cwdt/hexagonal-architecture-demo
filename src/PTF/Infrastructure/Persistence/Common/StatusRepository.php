<?php

namespace PTF\Infrastructure\Persistence\Common;

use DateTime;
use PTF\Domain\Status\Status;

abstract class StatusRepository {

    abstract protected function getAllPersistedStatuses(): array;
    abstract protected function persistStatuses(array $statuses): void;

    public function save(Status $status): void
    {
        $statuses = $this->getAllPersistedStatuses();
        $statuses[] = $status;

        $this->persistStatuses($statuses);
    }

    public function byDateTime(DateTime $dateTime): ?Status
    {
        $statuses = $this->getAllPersistedStatuses();

        $results = [];
        foreach ($statuses as $status) {
            if ($status->getFrom() <= $dateTime && $status->getUntil() >= $dateTime) {
                $results[] = $status;
            }
        }

        if (count($results) === 0) {
            return null;
        }

        usort($results, function (Status $left, Status $right) {
            return $left->getCreatedAt() <=> $right->getCreatedAt();
        });

        return array_pop($results);
    }
}