# Tromay — Instrucciones para Claude Code

Sistema Laravel para casa de cambio boliviana: gestión de transacciones FX, clientes,
tasas y noticias financieras, con API pública de tasas (`/api/rates`) consumida por el
ecosistema Kapitalya (serguicv, exchange-rate-alert-bolivia).

> La fuente autoritativa de producto y estado es `README.md`. Este archivo resume lo
> operativo para trabajar en el repo.

## Stack

- **Backend:** Laravel 9.19 · PHP 8.2 · Eloquent ORM
- **Auth:** Laravel Sanctum 3.0 (API + sesiones admin)
- **Frontend:** Blade · Vite 3 · Vanilla JS + Axios · Yajra DataTables 10 (server-side)
- **Datos:** MySQL 8.0 · Redis 7 (cache + sesiones)
- **Server:** Nginx + PHP-FPM 8.2 Alpine (Supervisor) — build multi-stage (Node 20 → Composer 2.7 → PHP-FPM)
- **Tests:** PHPUnit 9.5 · **TZ:** `America/La_Paz`

## Puertos / URLs

| Entorno | URL |
|---------|-----|
| Producción | https://tromay.kapitalya.com.bo (K8s namespace `public`, worker `desktop-worker3`) |
| Dev (Docker) | http://localhost:8080 · Mailpit 8025 · MySQL 33060 (`kapitalya_dev`) · Redis 6379 |

## Rutas clave

- **API pública** (throttle 60/min, sin auth): `GET /api/rates`, `/api/rates/{cash}`,
  `/api/rates/{cash}/history`, `/api/calculator`, health `/api/health/live` · `/api/health/ready`.
- **Público:** `/`, `/quote` (calculadora), `/dinero/{cash}`, `/noticia/{latest}`, `/feed.xml` (RSS).
- **Admin** (`auth`, prefijo `/admin`): `analytics`, `buy`/`sell`, `turn/*`, `reports/daily-close`,
  y CRUD resource de `cash`, `client`, `transaction`, `latest`, `quotation`.

## Estructura

```
app/Http/Controllers/{Api,Crud}/   # Api\RatesController, Crud\*CRUDController
app/Http/Requests/                 # Store*Request (validación)
app/Models/                        # Cash, Client, Transaction, Latest, Quotation, ...
app/Services/                      # RateService, DashboardService, TransactionService
database/{migrations,factories,seeders}/
resources/views/                   # Blade: layout/masteradmin.blade.php + módulos
routes/web.php                     # público + admin  |  routes/api.php  # health + datatable
```

## Correr en desarrollo

```bash
# Setup Docker (Windows): scripts\setup-dev.bat
docker compose -f docker-compose.dev.yml up -d
docker compose -f docker-compose.dev.yml exec app php artisan migrate --seed
docker compose -f docker-compose.dev.yml exec app php artisan test
```

## Convenciones

- CRUD admin: controlador en `Crud/`, `Store*Request` para validar, DataTables server-side
  (`Datatables::of(Model::query())`), vista `resources/views/<modulo>/crud.blade.php` con
  modal + `route('admin.<modulo>.*')`. Tasas y totales se calculan **en el servidor**
  (nunca confiar en valores del cliente).
- Dinero: columnas `decimal(15,4/6)`. Divisas viven en tabla `cashes` (modelo `Cash`).
- Cache de tasas via `RateService` (TTL 300s); invalidar con `RateService::invalidate()`.

## Sesión 2026-07-07 (claude/audit-modernize)

- **✓ Sidebar cotizaciones:** corregido `admin/cash` → `admin/quotation` en `masteradmin.blade.php`.
- **✓ Higiene Docker:** validado tags fijos, usuario no-root, `.dockerignore` presente.
- **✓ .gitignore:** confirmado que excluye `vendor/` y `/node_modules`.

## Pendientes

- **Reportes PDF/Excel:** el CHANGELOG menciona export a PDF/Excel; `ReportController::dailyClose`
  hoy solo renderiza HTML (`admin/reports/daily_close`). Falta la exportación descargable.
- **Cotización → transacción:** convertir una cotización vigente en una `Transaction` con un
  clic (no implementado; hoy la cotización es solo proforma/oferta).
- **Hub SSO:** el proyecto no implementa `hub_auth`; acceso desde el Hub por enlace público.

### No automatizable (requiere secretos/cluster)

- Deploy K8s (`docker save | ctr import` en `desktop-worker3`), DNS Cloudflare, `SENTRY_LARAVEL_DSN`.

## React Native

N/A — producto web-only (admin Laravel + sitio público). No hay app RN por diseño.
