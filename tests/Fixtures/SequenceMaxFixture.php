<?php

namespace Tests\Fixtures;

class SequenceMaxFixture
{
    public string $table = 'sequences';

    public array $records = [
        [
            'name' => 'promo',
            'current' => 500000,
        ],
    ];
}
