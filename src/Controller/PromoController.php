<?php

declare(strict_types=1);

namespace ExercisePromo\Controller;

use ExercisePromo\Service\IpLimiter;
use ExercisePromo\Service\PromoService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PromoController
{
    public function __construct(
        private IpLimiter $ipLimiter,
        private PromoService $promoService,
    ) {}


    public function getPromo(Request $request, Response $response): Response
    {
        $promoId = 0; // from session
        $promoCode = $this->promoService->getIssuedPromoCode($promoId);
        if ($promoCode) {
            return $this->promoRedirectResponse($promoCode, $response);
        }

        $ip = $request->getServerParams()['REMOTE_ADDR'];
        if ($this->ipLimiter->isLimitHit($ip)) {
            $response->getBody()->write('The number of promo codes issued for this ip is too high.');

            return $response;
        }

        $promoCode = $this->promoService->issuePromoCode($ip);
        if ($promoCode) {
            return $this->promoRedirectResponse($promoCode, $response);
        }

        $response->getBody()->write('Bad luck. We have ran out of promo codes.');

        return $response;
    }

    private function promoRedirectResponse(string $promoCode, Response $response): Response
    {
        return $response
            ->withHeader('Location', 'https://www.google.com/?query=' . $promoCode)
            ->withStatus(302);
    }
}
