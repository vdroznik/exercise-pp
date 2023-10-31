<?php

namespace Tests\Fixtures;

class PromoIssueFixture
{
    public string $table = 'promos_issues';

    public array $records = [
        [
            'id' => 1,
            'promo_id' => 1,
            'ip' => '127.0.0.1',
        ],
    ];
}
