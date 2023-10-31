<?php

use Doctrine\DBAL\Connection;
use Nyholm\Psr7\Factory\Psr17Factory;
use Odan\Session\MemorySession;
use Odan\Session\SessionInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;

return [
    ServerRequestFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },
    PDO::class => function (ContainerInterface $container) {
        return $container->get(Connection::class)->getNativeConnection();
    },
    SessionInterface::class => function (ContainerInterface $container) {
        $options = [
            'name' => 'exercise-promo',
            'lifetime' => 31_536_000, // 1 year in seconds
            'secure' => true,
            'httponly' => true,
            'cache_limiter' => 'nocache',
        ];

        return new MemorySession($options);
    },
];
