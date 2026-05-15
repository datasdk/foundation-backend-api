<?php

return [
    'blocks' => [
        ['route' => 'blocked.route.name'],
    ],
    'public' => [
        ['route' => 'public.route.name'],
    ],
    'permissions' => [
        'routes' => [
            'protected.route.name' => ['permission1', 'permission2'],
        ],
    ],
];
