<?php

return [
    'client_id' => env('PAYPAL_CLIENT_ID', ''),
    'secret' => env('PAYPAL_SECRET', ''),
    'settings' => [
        'mode' => env('PAYPAL_MODE', 'sandbox'), // Mode sandbox ou live
        'log.LogEnabled' => true,
        'log.FileName' => storage_path('/logs/paypal.log'),
        'log.LogLevel' => 'DEBUG', // DEBUG, INFO, WARN, ERROR
    ],
];
