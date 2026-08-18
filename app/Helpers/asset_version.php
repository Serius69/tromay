<?php

use Illuminate\Support\Facades\File;

if (! function_exists('kap_asset')) {
    /**
     * URL de un asset propio con huella de contenido (`?v=`).
     *
     * POR QUÉ: los assets se sirven con `Cache-Control: max-age=604800` y nombre
     * fijo, así que Cloudflare seguía entregando el CSS/JS viejo hasta una semana
     * después de un despliegue — verificado en producción: el origen ya tenía el
     * archivo nuevo y el borde devolvía el anterior. Con la huella, cada cambio
     * genera una URL distinta y el borde la trata como un recurso nuevo, sin
     * perder el cacheo largo de los que no cambiaron.
     *
     * La huella es el mtime del archivo (barato, sin leer contenido) y se memoiza
     * por request. Si el archivo no existe, se degrada a la URL simple.
     */
    function kap_asset(string $path): string
    {
        static $stamps = [];

        $path = ltrim($path, '/');

        if (! array_key_exists($path, $stamps)) {
            $full = public_path($path);
            $stamps[$path] = File::exists($full) ? (string) File::lastModified($full) : null;
        }

        $url = url($path);

        return $stamps[$path] === null ? $url : $url . '?v=' . $stamps[$path];
    }
}
