<?php

namespace App;

use bitExpert\Disco\BeanFactoryRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use PTF\Application\ChangeStatus\ChangeStatusHandler;
use PTF\Application\ChangeStatus\ChangeStatusValidator;
use PTF\Application\GetState\GetStateHandler;
use PTF\Domain\Status\Status;
use PTF\Infrastructure\CommandBus\Chain\Handle;
use PTF\Infrastructure\CommandBus\Chain\Validate;
use PTF\Infrastructure\CommandBus\Synchronous;
use PTF\Infrastructure\Persistence\Doctrine\StatusRepository;
use PTF\Infrastructure\Persistence\Filesystem\StatusRepository as FilesystemStatusRepository;
use PTF\Infrastructure\Persistence\Mock\DaytimeRepository;
use PTF\Infrastructure\QueryBus\QueryBus;
use PTF\Infrastructure\Ui\Cli\ChangeStatusCliHandler;
use PTF\Infrastructure\Ui\Cli\GetStateCliHandler;
use \bitExpert\Disco\Annotations\Configuration;
use \bitExpert\Disco\Annotations\Bean;
use \bitExpert\Disco\Annotations\Alias;

/**
 * @Configuration
 */
class PTFConfiguration
{
    /**
     * @Bean()
     */
    public function validate(): Validate
    {
        return new Validate(BeanFactoryRegistry::getInstance());
    }

    /**
     * @Bean()
     */
    public function handle(): Handle
    {
        return new Handle(BeanFactoryRegistry::getInstance());
    }

    /**
     * @Bean()
     */
    public function synchronousCommandBus(): Synchronous
    {
        return new Synchronous(
            [
                $this->validate(),
                $this->handle(),
            ]
        );
    }

    /**
     * @Bean()
     */
    public function queryBus(): QueryBus
    {
        return new QueryBus(BeanFactoryRegistry::getInstance());
    }

    /**
     * @Bean()
     */
    public function mockDaytimeRepository(): DaytimeRepository
    {
        return new DaytimeRepository();
    }

    /**
     * @Bean()
     */
    public function doctrineStatusRepository(): StatusRepository
    {
        return (new \Doctrine\ORM\Repository\DefaultRepositoryFactory())->getRepository(
            $this->doctrineEntityManager(),
            Status::class
        );
    }

    /**
     * @Bean()
     */
    public function filesystemStatusRepository(): FilesystemStatusRepository
    {
        return new FilesystemStatusRepository(__DIR__ . '/../var/status.txt');
    }

    /**
     * @Bean({
     *     "aliases"={
     *          @Alias({"type" = true})
     *     }
     * })
     */
    public function changeStatusValidator(): ChangeStatusValidator
    {
        return new ChangeStatusValidator($this->mockDaytimeRepository());
    }

    /**
     * @Bean({
     *     "aliases"={
     *          @Alias({"type" = true})
     *     }
     * })
     */
    public function changeStatusHandler(): ChangeStatusHandler
    {
        return new ChangeStatusHandler(
            $this->doctrineStatusRepository()
        );
    }

    /**
     * @Bean({
     *     "aliases"={
     *          @Alias({"type" = true})
     *     }
     * })
     */
    public function changeStatusCLIHandler(): ChangeStatusCliHandler
    {
        return new ChangeStatusCliHandler(
            $this->synchronousCommandBus()
        );
    }

    /**
     * @Bean({
     *     "aliases"={
     *          @Alias({"type" = true})
     *     }
     * })
     */
    public function getStateHandler(): GetStateHandler
    {
        return new GetStateHandler($this->mockDaytimeRepository(), $this->doctrineStatusRepository());
    }

    /**
     * @Bean({
     *     "aliases"={
     *          @Alias({"type" = true})
     *     }
     * })
     */
    public function getStateCLIHandler(): GetStateCliHandler
    {
        return new GetStateCliHandler($this->queryBus());
    }

    /**
     * @Bean()
     */
    public function doctrineEntityManager(): EntityManager
    {
        return EntityManager::create(
            [
                'driver'   => 'pdo_mysql',
                'host'     => 'hexagonal-mysql',
                'user'     => getenv('MYSQL_USER'),
                'password' => getenv('MYSQL_PASSWORD'),
                'dbname'   => getenv('MYSQL_DBNAME'),
            ],
            Setup::createYAMLMetadataConfiguration(
                [__DIR__ . '/../src/PTF/Infrastructure/Persistence/Doctrine/config'],
                getenv('ENV') === 'develop'
            )
        );
    }
}
