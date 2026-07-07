<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\JsonFormatter;

return [

    'default' => env('LOG_CHANNEL', 'stack'),

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace'   => false,
    ],

    'channels' => [

        // ── Primary stack for production ──────────────────────────────
        'stack' => [
            'driver'            => 'stack',
            'channels'          => ['daily', 'stderr'],
            'ignore_exceptions' => false,
        ],

        // ── Daily rotation — default in production (30 days) ──────────
        'daily' => [
            'driver'               => 'daily',
            'path'                 => storage_path('logs/laravel.log'),
            'level'                => env('LOG_LEVEL', 'debug'),
            'days'                 => 30,
            'replace_placeholders' => true,
        ],

        // ── Single file ───────────────────────────────────────────────
        'single' => [
            'driver'               => 'single',
            'path'                 => storage_path('logs/laravel.log'),
            'level'                => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        // ── stderr — JSON para Docker log collection / Loki ──────────
        'stderr' => [
            'driver'    => 'monolog',
            'level'     => env('LOG_LEVEL', 'info'),
            'handler'   => StreamHandler::class,
            'with'      => ['stream' => 'php://stderr'],
            'formatter' => JsonFormatter::class,
        ],

        // ── Slack — critical/emergency alerts only ────────────────────
        'slack' => [
            'driver'   => 'slack',
            'url'      => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Kapitalya Alerts',
            'emoji'    => ':rotating_light:',
            'level'    => env('LOG_SLACK_LEVEL', 'critical'),
        ],

        // ── Security events: auth failures, suspicious requests ───────
        'security' => [
            'driver' => 'daily',
            'path'   => storage_path('logs/security.log'),
            'level'  => 'info',
            'days'   => 90,
        ],

        // ── API activity: rate lookups, external calls ────────────────
        'api' => [
            'driver' => 'daily',
            'path'   => storage_path('logs/api.log'),
            'level'  => 'info',
            'days'   => 30,
        ],

        'null' => [
            'driver'  => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/emergency.log'),
        ],
    ],

];
