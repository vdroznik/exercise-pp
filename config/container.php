<?php

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Nyholm\Psr7\Request as NyholmPsr7Request;
use Nyholm\Psr7\Response as NyholmPsr7Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Random\Engine as RandomEngine;
use Random\Engine\Secure as RandomEngineSecure;
use Slim\Views\PhpRenderer;

return [
    PhpRenderer::class => function (ContainerInterface $container) {
        return new PhpRenderer(
            templatePath: __DIR__ . '/../views',
        );
    },

//    SessionInterface::class => function (ContainerInterface $container) {
//        $options = [
//            'name' => 'exercise-promo',
//            'secure' => true,
//            'httponly' => true,
//            'cache_limiter' => 'nocache',
//        ];
//
//        return new PhpSession($options);
//    },

    RandomEngine::class => DI\get(RandomEngineSecure::class),

    Connection::class => function (ContainerInterface $container) {
        return DriverManager::getConnection($container->get('db'));
    },

    RequestInterface::class => DI\get(NyholmPsr7Request::class),
    ResponseInterface::class => DI\get(NyholmPsr7Response::class),
];
