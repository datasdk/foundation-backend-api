<?php

return [
    'default' => env('FILESYSTEM_DRIVER', 'local'),
    
    'model' => null,

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'), // VIGTIGT: Korrekt sti
            'url' => env('APP_URL').'/storage',   // VIGTIGT: Tilføj /storage
            'visibility' => 'public',
        ],

        'storage' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'tmp' => [
            'driver' => 'local',
            'root' => storage_path('app/uploads/tmp'),
        ],
    ],

    'links' => [
        // KUN denne ene linje!
        public_path('storage') => storage_path('app/public'),
    ],
];

