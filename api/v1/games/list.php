<?php

// Static list for now; you can later map to real games scraped / configured.
$games = [
    [
        'id'   => 1,
        'name' => 'Dragon Slots',
        'code' => 'dragon_slots',
        'type' => 'slot',
    ],
    [
        'id'   => 2,
        'name' => 'Super Roulette',
        'code' => 'super_roulette',
        'type' => 'table',
    ],
    [
        'id'   => 3,
        'name' => '777 Jackpot',
        'code' => '777_jackpot',
        'type' => 'slot',
    ],
];

json_success(['games' => $games], 'Games list');
