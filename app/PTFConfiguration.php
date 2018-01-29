<?php

namespace App;

use bitExpert\Disco\Annotations\Bean;
use bitExpert\Disco\Annotations\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

/**
 * @Configuration
 */
class PTFConfiguration
{
    use Modules\CommandBusConfiguration;
    use Modules\RepositoryConfiguration;
    use Modules\UsecaseConfiguration;
    use Modules\CliConfiguration;

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
