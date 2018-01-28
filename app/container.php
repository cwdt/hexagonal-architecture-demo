<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Interop\Container\ContainerInterface;
use PTF\Application\ChangeStatus\ChangeStatusHandler;
use PTF\Application\ChangeStatus\ChangeStatusValidator;
use PTF\Application\GetState\GetStateHandler;
use PTF\Application\GetState\GetStateQuery;
use PTF\Domain\Daytime\DaytimeRepository;
use PTF\Domain\Status\Status;
use PTF\Domain\Status\StatusRepository;
use PTF\Infrastructure\CommandBus\Chain\Handle;
use PTF\Infrastructure\CommandBus\Chain\Validate;
use PTF\Infrastructure\CommandBus\CommandBusInterface;
use PTF\Infrastructure\CommandBus\Synchronous;
use PTF\Infrastructure\Persistence\Filesystem\StatusRepository as FilesystemStatusRepository;
use PTF\Infrastructure\QueryBus\QueryBusInterface;
use PTF\Infrastructure\QueryBus\QueryBus;
use PTF\Infrastructure\Persistence\Mock\DaytimeRepository as DaytimeRepositoryMock;
use PTF\Infrastructure\Ui\Cli\ChangeStatusCliHandler;
use PTF\Infrastructure\Ui\Cli\GetStateCliHandler;
use Symfony\Component\Debug\Debug;
use Xtreamwayz\Pimple\Container;

Debug::enable();

$container = new Container();

$dotEnv = new Dotenv\Dotenv(__DIR__ . DIRECTORY_SEPARATOR . '..');
$dotEnv->load();

// CommandBus
$container[Validate::class] = function (ContainerInterface $container) {
    return new Validate($container);
};

$container[Handle::class] = function (ContainerInterface $container) {
    return new Handle($container);
};

$container[CommandBusInterface::class] = function (ContainerInterface $container) {
    return new Synchronous(
        [
            $container->get(Validate::class),
            $container->get(Handle::class),
        ]
    );
};

// QueryBus
$container[QueryBusInterface::class] = function (ContainerInterface $container) {
    return new QueryBus($container);
};

// Repositories
$container[DaytimeRepository::class] = function () {
    return new DaytimeRepositoryMock();
};

$container[StatusRepository::class] = function (ContainerInterface $container) {
    return new FilesystemStatusRepository(__DIR__ . '/../var/status.txt');
//    return (new \Doctrine\ORM\Repository\DefaultRepositoryFactory())->getRepository(
//        $container->get(EntityManager::class),
//        Status::class
//    );
};

// Application
$container[ChangeStatusValidator::class] = function (ContainerInterface $container) {
    return new ChangeStatusValidator($container->get(DaytimeRepository::class));
};

$container[ChangeStatusHandler::class] = function (ContainerInterface $container) {
    return new ChangeStatusHandler($container->get(StatusRepository::class));
};

$container[ChangeStatusCliHandler::class] = function (ContainerInterface $container) {
    return new ChangeStatusCliHandler($container->get(CommandBusInterface::class));
};

$container[GetStateHandler::class] = function (ContainerInterface $container) {
    return new GetStateHandler($container->get(DaytimeRepository::class), $container->get(StatusRepository::class));
};

$container[GetStateCliHandler::class] = function (ContainerInterface $container) {
    return new GetStateCliHandler($container->get(QueryBusInterface::class));
};

$container[EntityManager::class] = function (ContainerInterface $container) {
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
};

return $container;