<?php

declare(strict_types=1);

namespace ExercisePromo;

use ExercisePromo\Controller\FrontController;
use ExercisePromo\Controller\PromoController;
use Psr\Container\ContainerInterface as Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class App
{
    public function __construct(
        protected Container $container
    ) {}

    public function handle(Request $request, Response $response = null): Response
    {
        if (!$response) {
            $response = $this->container->get(Response::class);
        }

        $uri = $request->getRequestTarget();

        if ($uri === '/getpromo') {
            $frontController = $this->container->get(PromoController::class);

            return $frontController->getPromo($request, $response);
        }

        $frontController = $this->container->get(FrontController::class);

        return $frontController->index($request, $response);
    }
}
