<?php

declare(strict_types=1);

namespace ExercisePromo\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PromoController
{

    public function getPromo(Request $request, Response $response): Response
    {
        return $response;
    }
}
