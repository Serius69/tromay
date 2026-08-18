<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Hosts aceptados en la cabecera `Host`.
     *
     * POR QUÉ EXISTE: Laravel construye `url()`/`route()` a partir del Host del
     * request, y de ahí salen el `<link rel=canonical>`, `og:url`, los `@id` del
     * JSON-LD y todos los `<loc>` del sitemap. Sin lista blanca, cualquiera que
     * alcance el origen (el contenedor publica 9111 en 0.0.0.0) puede inyectar
     * un Host arbitrario y hacer que la página se auto-declare canónica de otro
     * dominio.
     *
     * OJO AL DESPLEGAR: Symfony responde 400 a un Host no confiable, así que la
     * lista TIENE que incluir todo lo que llega de verdad:
     *   - el dominio público servido por el túnel de Cloudflare;
     *   - `localhost` / `127.0.0.1`, que usa el HEALTHCHECK del contenedor
     *     (`curl -f http://localhost/health`) — omitirlo deja el pod en unhealthy.
     * Se pueden añadir más por env `TRUSTED_HOSTS` (lista separada por comas).
     */
    public function hosts()
    {
        $extra = array_filter(array_map(
            'trim',
            explode(',', (string) env('TRUSTED_HOSTS', ''))
        ));

        return array_values(array_unique(array_filter(array_merge([
            $this->allSubdomainsOfApplicationUrl(),
            'localhost',
            '127.0.0.1',
        ], $extra))));
    }
}
