<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Captura de leads de email para alertas de tasas.
 *
 * Venta cruzada a alertas.kapitalya.com.bo: el público que mira las tasas de
 * la vitrina es el target perfecto de "avisame cuando el dólar llegue a mi
 * precio". Este endpoint solo PERSISTE el contacto en DB — NO depende de SMTP
 * (los MAIL_* del .env son placeholders). El registro real de la alerta lo
 * completa el usuario en el servicio de alertas, al que enlazamos con su email
 * como query param.
 */
class LeadController extends Controller
{
    /** Divisas cuyas alertas ofrecemos (coinciden con las cotizadas en vivo). */
    private const CURRENCIES = ['usd', 'eur', 'brl', 'ars', 'pen', 'clp'];

    /** Base del servicio de alertas para continuar el registro. */
    private const ALERTS_URL = 'https://alertas.kapitalya.com.bo';

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        // Honeypot anti-spam: el campo oculto `website` debe venir vacío. Si un
        // bot lo rellena, respondemos éxito falso (sin guardar) para no darle
        // ninguna señal de que fue detectado.
        if (filled($request->input('website'))) {
            return $this->success(
                $request,
                '¡Listo! Te avisaremos cuando el dólar llegue a tu precio.',
                null
            );
        }

        $validated = $request->validate([
            'email'    => ['required', 'email:rfc', 'max:255'],
            'currency' => ['nullable', 'in:' . implode(',', self::CURRENCIES)],
            'source'   => ['nullable', 'string', 'max:32'],
        ]);

        $email = mb_strtolower(trim($validated['email']));

        Lead::create([
            'email'    => $email,
            'currency' => $validated['currency'] ?? null,
            'source'   => $validated['source'] ?? 'home',
            'ip'       => $request->ip(),
        ]);

        // Enlace para continuar el registro en el servicio de alertas, con el
        // email pre-cargado.
        $continueUrl = self::ALERTS_URL . '/?' . http_build_query(array_filter([
            'email'    => $email,
            'currency' => $validated['currency'] ?? null,
            'utm_source' => 'tromay',
        ]));

        return $this->success(
            $request,
            '¡Listo! Te avisaremos cuando el dólar llegue a tu precio.',
            $continueUrl
        );
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
