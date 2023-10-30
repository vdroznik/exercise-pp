<?php

declare(strict_types=1);

namespace ExercisePromo\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class FrontController
{
    public function __construct(
        protected PhpRenderer $view,
    ) {}

    public function index(Request $request, Response $response): Response
    {
        return $this->view->render($response, "front.html.php");
    }
}
