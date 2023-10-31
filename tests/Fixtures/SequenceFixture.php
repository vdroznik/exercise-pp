<?php

namespace Tests\Fixtures;

class SequenceFixture
{
    public string $table = 'sequences';

    public array $records = [
        [
            'name' => 'promo',
            'current' => 0,
        ],
    ];
}
