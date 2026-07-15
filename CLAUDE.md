# Tromay — Instrucciones para Claude Code

**Vitrina pública (marketing) de Tromay Casa de Cambio** — sitio Laravel 100% público, sin
login ni backend transaccional. Posiciona a Tromay como la mejor casa de cambio física de
Bolivia, muestra tasas FX en vivo (fuente: **forex-erp**) y hace venta cruzada del ecosistema
Kapitalya. Expone además una API pública de tasas (`/api/rates`) consumida por el ecosistema
(serguicv, exchange-rate-alert-bolivia).

> **Refactor 2026-07-10:** se eliminó por completo el módulo de registro de transacciones y
> todo el panel admin (buy/sell, turnos, reportes, analytics, CRUD de transacción/cliente/
> cotización/divisa/noticia y el layout `masteradmin`). Tromay dejó de ser un ERP interno y
> pasó a ser una vitrina pública. Ver "Historial" abajo.

> La fuente autoritativa de producto es `README.md`. Este archivo resume lo operativo.

## Stack

- **Backend:** Laravel 9.19 · PHP 8.2 · Eloquent ORM (solo lectura pública; sin auth)
- **Frontend:** Blade + plantilla de marketing Kapitalya (`layout/master.blade.php`) · Vite 3 ·
  Vanilla JS + Axios · tema claro/oscuro
- **Datos:** MySQL 8.0 (tabla `cashes` = fallback de tasas; `latests` = noticias) · Redis 7 (cache)
- **Tasas:** **forex-erp** vía `EXCHANGE_API_URL` (fuente primaria); `cashes` sembrada = fallback
- **Server:** Nginx + PHP-FPM 8.2 Alpine (Supervisor) — build multi-stage · **TZ:** `America/La_Paz`
- **Tests:** PHPUnit 9.5 (SQLite en memoria)

> Nota: `yajra/laravel-datatables` sigue en `composer.json` pero ya no se usa (era del admin);
> candidato a quitar de las dependencias.

## Puertos / URLs

| Entorno | URL |
|---------|-----|
| Producción | https://tromay.kapitalya.com.bo — Docker Compose + Cloudflare Tunnel (K8s ns `private`, cluster Ubuntu pendiente) |
| Dev (Docker) | http://localhost:8080 · MySQL 33060 (`kapitalya_dev`) · Redis 6379 |

## Rutas (todas públicas)

- **Sitio:** `/`, `/about`, `/contact`, `/privacy`, `/terms`, `/quote` (simulador),
  `/dinero/{cash}` (detalle divisa), `/noticia/{latest}`, `/feed.xml` (RSS), `/health`.
- **API pública** (throttle 60/min, sin auth): `GET /api/rates`, `/api/rates/{cash}`,
  `/api/rates/{cash}/history`, `/api/calculator`, y `ext-rates*` (proxy directo a forex-erp).
  Health K8s: `/api/health/live` · `/api/health/ready`.
- **NO hay** rutas `/admin` ni auth — se eliminaron en el refactor 2026-07-10.

## Integración con forex-erp (tasas)

`App\Services\RateService::overlayForex()` obtiene las tasas primarias de forex-erp
(`GET {EXCHANGE_API_URL}/exchange-rates/primary/` → dict por código de divisa con
`buy_rate`/`sell_rate`/`official_rate`, base BOB) y las **sobrepone** sobre los registros
`Cash` sembrados (conservando id/ruta `dinero.show`). Si forex no responde, usa los valores
de la tabla `cashes` como fallback. Cache 300s. `EXCHANGE_API_URL` debe terminar en `/api/rates`
(dev: `http://localhost:9092/api/rates`; K8s: `http://forex-erp-backend:8007/api/rates`).
`ExchangeRateController` hace además de proxy directo bajo `/api/ext-rates*`.

## Estructura

```
app/Http/Controllers/
  HomeController.php          # páginas públicas (home, about, quote, ...)
  CashController.php          # /dinero/{cash}
  LatestController.php        # /noticia/{latest}
  FeedController.php          # RSS
  ExchangeRateController.php  # proxy forex /api/ext-rates*
  Api/RatesController.php     # /api/rates* (usa RateService)
app/Services/RateService.php  # tasas: forex primario + fallback Cash (overlay + cache)
app/Models/                   # Cash, CashRate, Latest, User (User queda por config/auth; sin uso)
resources/views/              # index (marketing), about, quote, cash/show, latest/show, layout/master
routes/web.php                # solo público   |  routes/api.php  # health checks
```

## Correr en desarrollo

```bash
docker compose -f docker-compose.dev.yml up -d
docker compose -f docker-compose.dev.yml exec app php artisan migrate --seed
docker compose -f docker-compose.dev.yml exec app php artisan test
# Para ver tasas reales, apuntar EXCHANGE_API_URL a forex-erp en el .env; sin eso, usa fallback DB.
```

## Rebuild / deploy de la imagen (gotcha crítico)

El stack "de producción" local es `docker-compose.yml` (proyecto `tromay`, app en `:9111`,
red externa `kapitalya-net`). Para reconstruir tras cambios de código:

```bash
docker compose -f docker-compose.yml build app queue_worker
docker compose -f docker-compose.yml up -d app queue_worker
docker compose -f docker-compose.yml exec app php artisan view:clear   # ← OBLIGATORIO, ver abajo
```

> ⚠️ **OPcache + volumen `app_storage` persistente:** la imagen fija
> `opcache.validate_timestamps=0` (`docker/php/opcache.ini`) y monta `app_storage:.../storage`,
> que **sobrevive al rebuild**. Las vistas Blade compiladas viejas quedan en el volumen y
> PHP-FPM las sirve desde OPcache aunque cambies el `.blade.php`. Reconstruir la imagen **no
> basta**: tras `up -d` hay que `php artisan view:clear` (y el contenedor nuevo arranca con
> OPcache vacío). Síntoma típico: el sitio renderiza el `master.blade` viejo (title
> `Kapitalya —`, sin JSON-LD) pese a tener el source nuevo dentro del contenedor.

## Convenciones

- Sitio de marketing en **español**, plantilla Kapitalya (clases `kap-*` + estilos inline).
- Dinero: columnas `decimal(15,4/6)`; divisas en tabla `cashes` (modelo `Cash`, nombre en minúscula).
- Las tasas se resuelven **siempre** vía `RateService` (nunca leer `Cash` crudo para mostrar tasas).
- La sección "Ecosistema Kapitalya" del home (`index.blade.php`, ancla `#ecosistema`) hace venta
  cruzada; sus enlaces son configurables por env (`ECO_*_URL`, `KAPITALYA_HUB_URL`).

## Historial

### Refactor 2026-07-10 — Vitrina pública (sin transacciones)
- **Eliminado**: todo el registro de transacciones y el panel admin — controladores
  `TransactionController`, `TurnController`, `DashboardController`, `ReportController`,
  `Api/TransactionDatatableController`, todos los `Crud/*`; modelos `Transaction`, `Client`,
  `Quotation`, `TurnSession`; requests `Store{Client,Quotation,Transaction}Request`,
  `UpdateCashRequest`, `StoreLatestRequest`; servicios `DashboardService`, `TransactionService`;
  vistas `admin/*`, `transaction/*`, `client/`, `quotation/`, `user/`, `cash/crud`,
  `latest/crud`, `layout/masteradmin`; y sus tests. Rutas `/admin` y `auth:sanctum` fuera.
- **Tasas → forex-erp**: `RateService` ahora obtiene las tasas de forex y las sobrepone sobre
  `Cash` (fallback DB). `EXCHANGE_API_URL` documentado en `.env.example`.
- **Marketing**: hero reposicionado ("la casa de cambio física #1 de Bolivia"), nueva sección
  "Ecosistema Kapitalya" (venta cruzada de forex-erp, Kapitalya Pay, Alerta de Tasas,
  ProformaPro, Katálogo, Insights), nav + metadatos SEO/OG actualizados.
- **Migraciones**: se conservan (tablas transaccionales quedan sin uso; no se borran para no
  romper el historial de migraciones). Candidato a limpieza futura si se rehace el schema.

### Pendientes
- (Opcional) Quitar `yajra/laravel-datatables` de `composer.json` (ya sin uso).
- (Opcional) Podar migraciones/tablas transaccionales huérfanas (`transactions`, `clients`,
  `quotations`, `turn_sessions`) si se decide un schema limpio.
- Confirmar/crear los subdominios de los productos del ecosistema o ajustar `ECO_*_URL`.
- Deploy K8s / DNS Cloudflare / rotación `mysql-tromay` (ver CLAUDE.md raíz — requiere cluster).

### Correcciones aplicadas en rebuild 2026-07-11
- **`composer.lock` desincronizado**: `composer.json` requería `sentry/sentry-laravel:^3.9`
  ausente en el lock → `composer install` (estricto) abortaba el build (exit 4). Regenerado el
  lock (`composer update --prefer-stable`); como `minimum-stability: dev` y no hay tag estable
  3.9.x (el estable saltó a 4.x), quedó pineado en `3.x-dev` (commit fijo, reproducible).
- **`sitemap.blade.php` rompía con `short_open_tag=On`**: la declaración `<?xml ...?>` se
  reinterpretaba como tag PHP (500 en `/sitemap.xml`). Se eliminó el view y el XML se construye
  ahora en `SitemapController::render()` (PHP puro, `htmlspecialchars(..., ENT_XML1)`).

## React Native

N/A — producto web-only (vitrina pública Laravel). No hay app RN por diseño.
