<?php

use Sentry\Tracing\SamplingContext;

return [

    'dsn' => env('SENTRY_LARAVEL_DSN', ''),

    'release' => env('GIT_COMMIT', null),

    'environment' => env('APP_ENV', 'production'),

    'breadcrumbs' => [
        'logs'                 => true,
        'sql_queries'          => true,
        'sql_bindings'         => false,  // no exponer valores de parámetros SQL
        'queue_info'           => true,
        'command_info'         => true,
        'http_client_requests' => true,
    ],

    'tracing' => [
        'queue_job_transactions' => true,
        'queue_jobs'             => true,
        'sql_queries'            => true,
        'sql_origin'             => false,
        'http_client_requests'   => true,
        'redis_commands'         => false,
    ],

    'traces_sample_rate' => (float) env('SENTRY_TRACES_RATE', 0.1),

    'profiles_sample_rate' => (float) env('SENTRY_PROFILES_RATE', 0.05),

    'send_default_pii' => false,

    // Ignorar excepciones que no aportan señal de error real
    'ignore_exceptions' => [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Validation\ValidationException::class,
        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
        \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class,
        \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException::class,
    ],

    'before_send' => static function (\Sentry\Event $event): ?\Sentry\Event {
        $sensitiveFields = ['password', 'password_confirmation', 'token', 'secret', 'pin'];

        $request = $event->getRequest();
        if ($request !== null && isset($request['data']) && is_array($request['data'])) {
            foreach ($sensitiveFields as $field) {
                if (array_key_exists($field, $request['data'])) {
                    $request['data'][$field] = '[FILTERED]';
                }
            }
        }

        return $event;
    },
];
