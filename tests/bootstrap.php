<?php

use DI\ContainerBuilder;
use ExercisePromo\App;

require __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../config/config.php');
$containerBuilder->addDefinitions(__DIR__ . '/../config/config_test.php');
$containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');
$containerBuilder->addDefinitions(__DIR__ . '/../config/container_test.php');
$container = $containerBuilder->build();


$GLOBALS['container'] = $container;
$GLOBALS['app'] = new App($container);
