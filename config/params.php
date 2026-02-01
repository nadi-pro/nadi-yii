<?php

return [
    'nadi' => [
        'enabled' => true,
        'driver' => 'log', // log, http, opentelemetry

        'connections' => [
            'log' => [
                'path' => '@runtime/nadi',
            ],
            'http' => [
                'api_key' => $_ENV['NADI_API_KEY'] ?? '',
                'app_key' => $_ENV['NADI_APP_KEY'] ?? '',
                'endpoint' => 'https://nadi.pro/api',
                'version' => 'v1',
            ],
            'opentelemetry' => [
                'endpoint' => 'http://localhost:4318',
                'service_name' => $_ENV['APP_NAME'] ?? 'yii-app',
                'service_version' => '1.0.0',
                'environment' => $_ENV['APP_ENV'] ?? 'production',
            ],
        ],

        'query' => [
            'slow_threshold' => 500, // milliseconds
        ],

        'http' => [
            'hidden_request_headers' => [
                'Authorization',
                'php-auth-pw',
            ],
            'hidden_parameters' => [
                'password',
                'password_confirmation',
            ],
            'ignored_status_codes' => [
                '200-307',
            ],
        ],

        'sampling' => [
            'strategy' => 'fixed_rate', // fixed_rate, dynamic_rate, interval, peak_load
            'config' => [
                'sampling_rate' => 0.1,
                'base_rate' => 0.05,
                'load_factor' => 1.0,
                'interval_seconds' => 60,
            ],
        ],
    ],
];
