<?php

use DI\ContainerBuilder;
use ExercisePromo\App;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../config/config.php');
$containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');
$container = $containerBuilder->build();

$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator(
    serverRequestFactory: $psr17Factory,
    uriFactory: $psr17Factory,
    uploadedFileFactory: $psr17Factory,
    streamFactory: $psr17Factory
);
$request = $creator->fromGlobals();
$response = $container->get(Response::class);

$app = $container->get(App::class);
$response = $app->handle($request, $response);

$emitter = new SapiEmitter();
$emitter->emit($response);
