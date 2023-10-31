<?php

declare(strict_types=1);

namespace ExercisePromo\Controller;

use ExercisePromo\Service\IpLimiter;
use ExercisePromo\Service\PromoService;
use Odan\Session\SessionManagerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

class PromoController
{
    public function __construct(
        private IpLimiter $ipLimiter,
        private PromoService $promoService,
        private SessionManagerInterface $session,
        private LoggerInterface $logger,
    ) {}


    public function getPromo(Request $request, Response $response): Response
    {
        $ip = $request->getServerParams()['REMOTE_ADDR'];
        $this->logger->info('Get Promo request', ['ip' => $ip]);

        if (!$this->session->isStarted()) {
            $this->session->start();
        }

        $promoId = $this->session->get('promoId');
        if ($promoId) {
            $promoCode = $this->promoService->getIssuedPromoCode($promoId);
            if ($promoCode) {
                $this->logger->info('Giving out existing promo', ['promoId' => $promoId, 'ip' => $ip]);
                return $this->promoRedirectResponse($promoCode, $response);
            }
        }

        if ($this->ipLimiter->isLimitHit($ip)) {
            $this->logger->warning('The number of promo codes issued for ip is too high', ['ip' => $ip]);
            $response->getBody()->write('The number of promo codes issued for this ip is too high.');

            return $response;
        }

        $promo = $this->promoService->issuePromo($ip);
        if (!$promo) {
            $this->logger->alert('We are out of promocodes', ['ip' => $ip]);
            $response->getBody()->write('Bad luck. We have ran out of promo codes.');

            return $response;
        }

        $this->session->set('promoId', $promo->id);
        $this->session->save();

        $this->logger->info('Issued the next promo', ['promoId' => $promo->code, 'ip' => $ip]);

        return $this->promoRedirectResponse($promo->code, $response);
    }

    private function promoRedirectResponse(string $promoCode, Response $response): Response
    {
        return $response
            ->withHeader('Location', 'https://www.google.com/?query=' . $promoCode)
            ->withStatus(302);
    }
}
