<?php

declare(strict_types=1);

namespace ExercisePromo\Service;

use ExercisePromo\Repository\IpRepository;

class IpLimiter
{
    private const MAX_PROMOS_PER_IP = 1000;

    public function __construct(
        private IpRepository $ipRepo,
    ) {}

    public function isLimitHit(string $ip): bool
    {
        $cnt = $this->ipRepo->getCountByIp($ip);

        return $cnt >= self::MAX_PROMOS_PER_IP;
    }
}
