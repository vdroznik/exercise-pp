<?php

declare(strict_types=1);

namespace ExercisePromo\Repository;

use Doctrine\DBAL\Connection;

class IpRepository
{
    public function __construct(
        private Connection $dbal
    ) {}

    public function getCountByIp(string $ip): int
    {
        $ipp = ip2long($ip);
        $result = $this->dbal->fetchOne('SELECT cnt FROM ips WHERE ipp = :ipp', ['ipp' => $ipp]);

        return (int) $result;
    }

    public function incrementCountForIp(string $ip): bool
    {
        $ipp = ip2long($ip);
        $affected = $this->dbal->executeStatement('INSERT INTO ips SET ipp = :ipp, cnt = 1 ON DUPLICATE KEY UPDATE cnt = cnt + 1', ['ipp' => $ipp]);

        return ((int) $affected) > 0;
    }
}
