<?php

return [
    'temperature' => [
        'cache_key' => 'furnace.temperature',
        'max' => 80,
        'min' => 30,
        'ready' => 40,
    ],

    'mode' => [
        'cache_key' => 'furnace.mode',
    ],

    'notification' => [
        'between' => ['8:00', '23:00'],
    ],
];
