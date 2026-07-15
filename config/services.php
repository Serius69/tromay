<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'exchange_api' => [
        'url' => env('EXCHANGE_API_URL', ''),
        // Margen comercial mínimo (% entre compra y venta). Si forex devuelve un
        // spread menor a este, RateService lo ensancha alrededor del punto medio
        // para que ninguna divisa se muestre con ganancia ~0%. 0 = respetar forex.
        'min_spread_pct' => (float) env('EXCHANGE_MIN_SPREAD_PCT', 0),
    ],

    // Monetización — anuncios (Google AdSense) y analítica. Todo desactivado por
    // defecto: sin IDs configurados no se carga ningún script de terceros y solo
    // se muestran cookies técnicas. Los scripts se cargan únicamente tras el
    // consentimiento del visitante (ver banner de cookies en layout/master).
    'adsense' => [
        'client' => env('ADSENSE_CLIENT', ''), // ca-pub-XXXXXXXXXXXXXXXX
        'slot'   => env('ADSENSE_SLOT', ''),   // id de bloque display responsive (opcional)
    ],

    'analytics' => [
        'ga_id' => env('GA_MEASUREMENT_ID', ''), // G-XXXXXXXXXX (opcional)
    ],

];
