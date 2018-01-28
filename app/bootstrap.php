<?php

use App\PTFConfiguration;
use Symfony\Component\Debug\Debug;

Debug::enable();

$dotEnv = new Dotenv\Dotenv(__DIR__ . DIRECTORY_SEPARATOR . '..');
$dotEnv->load();

$beanFactory = new \bitExpert\Disco\AnnotationBeanFactory(PTFConfiguration::class);
\bitExpert\Disco\BeanFactoryRegistry::register($beanFactory);