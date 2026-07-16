{{--
    partials/ad.blade.php — Unidad de anuncio AdSense reutilizable (env-gated).

    Se renderiza SOLO si config('services.adsense.client') está seteado (y hay un slot).
    Los anuncios cargan tras el consentimiento de cookies: el loader de master.blade.php
    hace push de cada `ins.adsbygoogle.kap-ad-slot`, así que esta unidad se activa sola.

    Parámetros (todos opcionales):
      $slot      — ID del slot de AdSense. Default: config('services.adsense.slot').
                   Para unidades in-content conviene usar un slot propio.
      $format    — data-ad-format. Default: 'auto'.
      $responsive— data-full-width-responsive. Default: 'true'.
      $label     — etiqueta legal sobre el bloque. Default: 'Publicidad'.
      $maxWidth  — ancho máximo del contenedor en px. Default: 970.
      $minHeight — alto mínimo del <ins> en px (evita saltos de layout). Default: 90.
      $margin    — margen CSS del contenedor. Default: '24px auto'.

    Uso: @include('partials.ad', ['slot' => '1234567890', 'maxWidth' => 728])
--}}
@php
    $adClient     = config('services.adsense.client');
    $adSlot       = $slot ?? config('services.adsense.slot');
    $adFormat     = $format ?? 'auto';
    $adResponsive = $responsive ?? 'true';
    $adLabel      = $label ?? 'Publicidad';
    $adMaxWidth   = $maxWidth ?? 970;
    $adMinHeight  = $minHeight ?? 90;
    $adMargin     = $margin ?? '24px auto';
@endphp
@if($adClient && $adSlot)
<div class="container kap-ad" style="max-width:{{ (int) $adMaxWidth }}px;margin:{{ $adMargin }};">
    <div style="text-align:center;font-size:10px;letter-spacing:.08em;text-transform:uppercase;color:var(--kap-text-muted,#8a90a6);margin-bottom:6px;">{{ $adLabel }}</div>
    <ins class="adsbygoogle kap-ad-slot"
         style="display:block;min-height:{{ (int) $adMinHeight }}px;"
         data-ad-client="{{ $adClient }}"
         data-ad-slot="{{ $adSlot }}"
         data-ad-format="{{ $adFormat }}"
         data-full-width-responsive="{{ $adResponsive }}"></ins>
</div>
@endif
