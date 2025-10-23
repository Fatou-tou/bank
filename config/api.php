<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Version de l'API
    |--------------------------------------------------------------------------
    |
    | Cette valeur indique la version actuelle de ton API.
    | Exemple : v1, v2, etc.
    |
    */
    'version' => env('API_VERSION', 'v1'),

    /*
    |--------------------------------------------------------------------------
    | URL de base de l'API
    |--------------------------------------------------------------------------
    |
    | On concatène l'URL de l'application avec la version de l'API.
    | Exemple : http://api.banque.example.com/api/v1
    |
    */
    'base_url' => env('APP_URL') . '/api/' . env('API_VERSION', 'v1'),
];
