<?php

return [
    'namespaces' => [
        'models' => 'App\\Models\\',
        'controllers' => 'App\\Http\\Controllers\\'
    ],
    'auth' => [
        'guard' => 'api'
    ],
    'specs' => [
        'info' => [
            'title' => env('APP_NAME'),
            'description' => null,
            'terms_of_service' => null,
            'contact' => [
                'name' => null,
                'url' => null,
                'email' => null,
            ],
            'license' => [
                'name' => null,
                'url' => null,
            ],
            'version' => '1.0.0',
        ],
        'servers' => [
            ['url' => env('APP_URL').'/api', 'description' => 'Default Environment'],
        ],
        'tags' => []
    ],
    'transactions' => [
        'enabled' => false,
    ],
    'search' => [
        'case_sensitive' => true, // TODO: set to "false" by default in 3.0 release
        "max_nested_depth" => 10
    ],

    // WHITELIST ORION

    "whitelist" =>[

        'includes' => [
            'tags',
            'categories',
            'available',
            'images',
            'image',
            'address',
            'addresses',
            'contacts',
            'contact',
            'settings'
        ],
        'alwaysIncludes'=>[

        ],
        "sortableBy" => [
            'id', 
            'sorting',
            'created_at',
            'updated_at',
            
            'available.from',
            'available.to',
        ],
        "exposedScopes" => [
            'access',
            'available',
            'availableBetween',
            'availableAtDate',
            'availableBetweenDate',

            'availableNow',
            'availableFrom',
            'availableTo',
            'availableAt',

            'HasRelations',
            'HasCategory',
            'category',
            'categories',
            'sortingById',
            'sortNested',
            'settings',
            'withAllTags',
            'withAnyTags',
            'withAnyTagsOfAnyType',  
            'withAllTagsOfAnyType',
            'withAllTagsFromArray',
            'withCategory',
            'withCategories',
            'withAddresses',
            'availableAt',
            'HasRelations',
            "WithinDistanceOf",
            "WithinDistanceOfAddress",
            "OrderByDistanceFrom",
            'OrderByRaw',        
        ],
        "filterableBy" => [
            "id",
            "status",
            "created_at",
            "updated_at",
            "categories.*",
            "addresses.*",
            "available.*"
        ],
        "searchableBy" => [
      
        ],
        "aggregates" => [

        ]
    ]
    
         
];
