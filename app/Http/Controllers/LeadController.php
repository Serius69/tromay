<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Lista de espera ("waitlist") para futuras alertas de tasas.
 *
 * HONESTIDAD DEL FUNNEL — leer antes de tocar el copy:
 * Tromay NO envía correos hoy. No hay SMTP configurado (los MAIL_* del .env son
 * placeholders) y el antiguo servicio alertas.kapitalya.com.bo fue dado de baja
 * (hoy redirige a Paralelo). Por eso este endpoint NO promete "te avisaremos":
 * solo registra el interés en una lista de espera y deriva al usuario a
 * Paralelo, que SÍ existe y muestra el dólar en vivo ahora mismo.
 *
 * Si algún día se habilitan las alertas de verdad (SMTP + precio objetivo +
 * baja con token), recién ahí puede volver el copy de "te avisamos".
 */
class LeadController extends Controller
{
    /** Divisas de interés que ofrecemos (coinciden con las cotizadas en vivo). */
    private const CURRENCIES = ['usd', 'eur', 'brl', 'ars', 'pen', 'clp'];

    /**
     * Destino real para seguir el dólar HOY. Paralelo está vivo y es el
     * producto del ecosistema que muestra el mercado en tiempo real.
     * (alertas.kapitalya.com.bo quedó dado de baja y solo redirige aquí.)
     */
    private const TRACKER_URL = 'https://paralelo.kapitalya.com.bo';

    /** Copy único de confirmación — describe lo que REALMENTE pasa. */
    private const SUCCESS_MSG = 'Listo, anotamos tu email en la lista de espera de alertas. Mientras tanto podés seguir el dólar en vivo:';

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        // Honeypot anti-spam: el campo oculto `website` debe venir vacío. Si un
        // bot lo rellena, respondemos éxito falso (sin guardar) para no darle
        // ninguna señal de que fue detectado.
        if (filled($request->input('website'))) {
            return $this->success($request, self::SUCCESS_MSG, null);
        }

        $validated = $request->validate([
            // `not_regex` sobre caracteres de control: CVE-2026-48019 (inyección
            // CRLF en la regla `email`) no tiene parche para Laravel 9, que ya
            // está fuera de soporte. Hoy el email solo se guarda, pero en cuanto
            // alguien lo use para enviar correo un \r\n embebido permitiría
            // inyectar cabeceras. Se rechaza en la puerta de entrada.
            'email'    => ['required', 'email:rfc', 'max:255', 'not_regex:/[\x00-\x1F\x7F]/'],
            'currency' => ['nullable', 'in:' . implode(',', self::CURRENCIES)],
            'source'   => ['nullable', 'string', 'max:32'],
        ]);

        $email = mb_strtolower(trim($validated['email']));

        // firstOrCreate + índice UNIQUE en `email`: reenviar el formulario no
        // duplica el lead ni permite inflar la tabla con el mismo contacto.
        Lead::firstOrCreate(
            ['email' => $email],
            [
                'currency' => $validated['currency'] ?? null,
                'source'   => $validated['source'] ?? 'home',
                'ip'       => $request->ip(),
            ]
        );

        // Derivación a Paralelo (producto vivo). NO se propaga el email: no hace
        // falta para ver el mercado y evita filtrar un dato personal por la URL
        // hacia otro host, el Referer y los logs de acceso.
        $continueUrl = self::TRACKER_URL . '/?' . http_build_query(array_filter([
            'currency'   => $validated['currency'] ?? null,
            'utm_source' => 'tromay',
        ]));

        return $this->success($request, self::SUCCESS_MSG, $continueUrl);
    }

    /**
     * Respuesta unificada: JSON para fetch (AJAX), redirect con flash para
     * envíos de formulario normales.
     */
    private function success(Request $request, string $message, ?string $continueUrl): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok'           => true,
                'message'      => $message,
                'continue_url' => $continueUrl,
            ]);
        }

        return back()->with('lead_success', $message)
                     ->with('lead_continue_url', $continueUrl);
    }
}
