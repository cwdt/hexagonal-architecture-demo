<?php

namespace PTF\Infrastructure\Persistence\Filesystem;

use DateTime;
use PTF\Domain\Status\Status;
use PTF\Domain\Status\StatusRepository as StatusRepositoryInterface;
use PTF\Infrastructure\Persistence\Common\StatusRepository as AbstractStatusRepository;

class StatusRepository extends AbstractStatusRepository implements StatusRepositoryInterface
{
    /** @var string */
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    protected function getAllPersistedStatuses(): array
    {
        $fileContents = file_get_contents($this->filename);

        if (empty($fileContents)) {

            return [];
        }

        $statuses = unserialize($fileContents);

        return $statuses;
    }

    protected function persistStatuses(array $statuses): void
    {
        file_put_contents($this->filename, serialize($statuses));
    }
}