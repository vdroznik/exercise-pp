<?php

declare(strict_types=1);

namespace ExercisePromo\Controller;

use ExercisePromo\Service\IpLimiter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PromoController
{
    public function __construct(
        private IpLimiter $ipLimiter,
    ) {}


    public function getPromo(Request $request, Response $response): Response
    {
        $ip = $request->getServerParams()['REMOTE_ADDR'];

        if ($this->ipLimiter->isLimitHit($ip)) {
            $response->getBody()->write('The number of promo codes issued for this ip is too high.');

            return $response;
        }

        return $response;
    }
}
