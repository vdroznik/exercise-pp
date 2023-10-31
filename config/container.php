<?php

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\Logger;
use Nyholm\Psr7\Request as NyholmPsr7Request;
use Nyholm\Psr7\Response as NyholmPsr7Response;
use Odan\Session\PhpSession;
use Odan\Session\SessionInterface;
use Odan\Session\SessionManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Random\Engine as RandomEngine;
use Random\Engine\Secure as RandomEngineSecure;
use Slim\Views\PhpRenderer;

return [
    PhpRenderer::class => function (ContainerInterface $container) {
        return new PhpRenderer(
            templatePath: __DIR__ . '/../views',
        );
    },

    SessionManagerInterface::class => function (ContainerInterface $container) {
        return $container->get(SessionInterface::class);
    },
    SessionInterface::class => function (ContainerInterface $container) {
        $options = [
            'name' => 'exercise-promo',
            'lifetime' => 31_536_000, // 1 year in seconds
            'secure' => true,
            'httponly' => true,
            'cache_limiter' => 'nocache',
        ];

        return new PhpSession($options);
    },

    RandomEngine::class => DI\get(RandomEngineSecure::class),

    Connection::class => function (ContainerInterface $container) {
        return DriverManager::getConnection($container->get('db'));
    },

    RequestInterface::class => DI\get(NyholmPsr7Request::class),
    ResponseInterface::class => DI\get(NyholmPsr7Response::class),

    LoggerInterface::class => function () {
        $logger = new Logger('app');
        $filename = dirname(__DIR__) . '/logs/app.log';
        $rotatingFileHandler = new RotatingFileHandler($filename, 10, Level::Debug, true, 0777);
        $rotatingFileHandler->setFormatter(new LineFormatter(null, 'Y-m-s H:i:s', false, true));
        $logger->pushHandler($rotatingFileHandler);

        return $logger;
    },
];
