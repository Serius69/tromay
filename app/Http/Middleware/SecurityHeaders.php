<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    /**
     * Construye el CSP. Relaja hacia los dominios de Google (AdSense/GA) SOLO si
     * la monetización está configurada (`ADSENSE_CLIENT`/`GA_MEASUREMENT_ID`);
     * si no, el CSP queda estricto e idéntico al original. Sin esto, el script de
     * AdSense inyectado tras el consentimiento (layout/master) queda bloqueado.
     */
    private function csp(): string
    {
        $adsOn = (bool) (config('services.adsense.client') || config('services.analytics.ga_id'));

        $gScript  = $adsOn ? ' https://pagead2.googlesyndication.com https://*.googlesyndication.com https://www.googletagmanager.com https://www.google-analytics.com https://adservice.google.com https://partner.googleadservices.com' : '';
        $gImg     = $adsOn ? ' https://*.googlesyndication.com https://*.doubleclick.net https://*.google.com https://*.google-analytics.com' : '';
        $gConnect = $adsOn ? ' https://pagead2.googlesyndication.com https://*.google-analytics.com https://*.doubleclick.net https://*.googlesyndication.com' : '';
        $gFrame   = $adsOn ? ' https://googleads.g.doubleclick.net https://*.doubleclick.net https://*.googlesyndication.com' : '';

        return "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' https://www.google.com/maps https://maps.googleapis.com https://static.cloudflareinsights.com https://cdn.jsdelivr.net{$gScript}; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
            "font-src 'self' https://fonts.gstatic.com data:; " .
            "img-src 'self' data: blob: https://www.google.com/maps https://maps.gstatic.com https://maps.googleapis.com{$gImg}; " .
            "frame-src https://www.google.com/maps{$gFrame}; " .
            "connect-src 'self' https://cloudflareinsights.com{$gConnect}; " .
            "object-src 'none'; " .
            "base-uri 'self'; " .
            "form-action 'self';";
    }

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=(), payment=()');
        $response->headers->set('Content-Security-Policy', $this->csp());

        // HSTS: only send over HTTPS in production
        if (config('app.env') === 'production') {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Strip server fingerprinting headers
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}
