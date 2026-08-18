<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    // Cloudflare Tunnel → Nginx: confiamos en los proxies para que X-Forwarded-For
    // resuelva la IP real del cliente (rate-limit por IP, no bucket global).
    //
    // NO usar '*': con '*' se confía en TODA la cadena XFF, así que un cliente
    // que mande "X-Forwarded-For: 1.2.3.4" spoofea su IP y evade el throttle
    // 60/min de la API. Los proxies reales (cloudflared + nginx del Compose)
    // viven en redes privadas RFC1918/loopback — solo esos rangos se confían;
    // el primer salto público (la IP real que anota Cloudflare) queda como IP
    // del cliente y lo que el cliente haya inventado a su izquierda se ignora.
    protected $proxies = [
        '127.0.0.1',
        '10.0.0.0/8',
        '172.16.0.0/12',
        '192.168.0.0/16',
    ];

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
