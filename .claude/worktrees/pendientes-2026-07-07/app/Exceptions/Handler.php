<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    // Known user-facing errors — not worth cluttering logs
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        ModelNotFoundException::class,
        NotFoundHttpException::class,
        ValidationException::class,
        TooManyRequestsHttpException::class,
        MethodNotAllowedHttpException::class,
    ];

    public function register(): void
    {
        // Forward unhandled exceptions to Sentry when configured
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });

        // Structured JSON for all API requests
        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return $this->jsonError($e);
            }
        });

        // Web: 429 — friendly rate-limit page
        $this->renderable(function (TooManyRequestsHttpException $e, Request $request) {
            if (! $request->expectsJson()) {
                $retryAfter = $e->getHeaders()['Retry-After'] ?? 60;
                return response()->view('errors.429', compact('retryAfter'), 429);
            }
        });
    }

    // ─────────────────────────────────────────────────────────────────
    // JSON error shape — consistent across the whole API
    // ─────────────────────────────────────────────────────────────────
    private function jsonError(Throwable $e): JsonResponse
    {
        $status = $this->httpStatusFor($e);

        $payload = [
            'error'   => true,
            'status'  => $status,
            'message' => $this->userMessage($status),
        ];

        // Validation errors expose field-level details — safe to send
        if ($e instanceof ValidationException) {
            $payload['errors'] = $e->errors();
        }

        // Stack trace only in local/debug — never in production
        if (config('app.debug')) {
            $payload['debug'] = [
                'exception' => get_class($e),
                'message'   => $e->getMessage(),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
            ];
        }

        return response()->json($payload, $status);
    }

    private function httpStatusFor(Throwable $e): int
    {
        return match (true) {
            $e instanceof ValidationException            => 422,
            $e instanceof AuthenticationException        => 401,
            $e instanceof AuthorizationException         => 403,
            $e instanceof ModelNotFoundException,
            $e instanceof NotFoundHttpException          => 404,
            $e instanceof MethodNotAllowedHttpException  => 405,
            $e instanceof TooManyRequestsHttpException   => 429,
            $e instanceof HttpException                  => $e->getStatusCode(),
            default                                      => 500,
        };
    }

    private function userMessage(int $status): string
    {
        return match ($status) {
            401 => 'No autorizado. Inicia sesión para continuar.',
            403 => 'No tienes permiso para realizar esta acción.',
            404 => 'El recurso solicitado no existe.',
            405 => 'Método no permitido.',
            422 => 'Los datos enviados no son válidos.',
            429 => 'Demasiadas solicitudes. Espera un momento e intenta nuevamente.',
            500 => 'Ocurrió un error interno. Estamos trabajando en solucionarlo.',
            default => 'Ocurrió un error inesperado.',
        };
    }

    // ─────────────────────────────────────────────────────────────────
    // Add request context to every log entry automatically
    // ─────────────────────────────────────────────────────────────────
    protected function context(): array
    {
        $context = parent::context();

        if ($request = request()) {
            $context['url']    = $request->fullUrl();
            $context['method'] = $request->method();
            $context['ip']     = $request->ip();
        }

        $context['app_version'] = env('APP_VERSION', 'dev');

        return $context;
    }
}
