<?php

namespace App\Modules;

use bitExpert\Disco\Annotations\Bean;
use Doctrine\ORM\Repository\DefaultRepositoryFactory;
use PTF\Domain\Daytime\DaytimeRepository;
use PTF\Domain\Status\Status;
use PTF\Domain\Status\StatusRepository;
use PTF\Infrastructure\Persistence\Doctrine\StatusRepository as DoctrineStatusRepository;
use PTF\Infrastructure\Persistence\Filesystem\StatusRepository as FilesystemStatusRepository;
use PTF\Infrastructure\Persistence\Mock\DaytimeRepository as MockDaytimeRepository;

trait RepositoryConfiguration
{
    /**
     * @Bean()
     */
    public function getStatusRepository(): StatusRepository
    {
        return $this->getDoctrineStatusRepository();
    }

    /**
     * @Bean()
     */
    public function getDaytimeRepository(): DaytimeRepository
    {
        return $this->getMockDaytimeRepository();
    }

    /**
     * @Bean()
     */
    public function getMockDaytimeRepository(): MockDaytimeRepository
    {
        return new MockDaytimeRepository();
    }

    /**
     * @Bean()
     */
    protected function getDoctrineStatusRepository(): DoctrineStatusRepository
    {
        return (new DefaultRepositoryFactory())->getRepository(
            $this->doctrineEntityManager(),
            Status::class
        );
    }

    /**
     * @Bean()
     */
    protected function getFilesystemStatusRepository(): FilesystemStatusRepository
    {
        return new FilesystemStatusRepository(__DIR__ . '/../var/status.txt');
    }
}