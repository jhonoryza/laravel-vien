<?php
return [
    'generator' => [
        'route_path' => 'routes' . DIRECTORY_SEPARATOR . 'web.php',

        'controller' => [
            'namespace' => 'App\\Http\\Controllers',
            'path' => 'app' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Controllers',
        ],

        'model' => [
            'namespace' => 'App\\Models',
            'path' => 'app' . DIRECTORY_SEPARATOR . 'Models',
        ],
    ]
];