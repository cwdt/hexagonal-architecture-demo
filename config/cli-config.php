<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

$container = require __DIR__ . '/../app/bootstrap.php';

$entityManager = $container->get(EntityManager::class);

return ConsoleRunner::createHelperSet($entityManager);