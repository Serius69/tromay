# Tromay — Casa de Cambio Física #1 de Bolivia (vitrina pública)

> Sitio Laravel **100% público** que posiciona a Tromay como la mejor casa de cambio física de Bolivia: muestra tasas FX en vivo (fuente: forex-erp), hace venta cruzada del ecosistema fintech Kapitalya y expone una API pública de tasas consumida por todo el ecosistema. Sin login ni registro de transacciones (ese módulo se retiró el 2026-07-10).

**Stack:** Laravel 9 · PHP 8.2 · MySQL 8.0 · Redis 7 · **URL:** public.kapitalya.com.bo · **Estado:** en producción vía Docker Compose / Cloudflare Tunnel; cluster K8s Docker Desktop abandonado el 2026-07-08 → cluster Ubuntu pendiente

---

## Qué es

Tromay es la **cara pública** del ecosistema Kapitalya: una casa de cambio física con más de 30 años de trayectoria en La Paz. El sitio cumple tres objetivos:

1. **Posicionamiento** — presentar a Tromay como la casa de cambio física líder de Bolivia: trayectoria, atención en sucursal y las mejores tasas del mercado.
2. **Tasas en vivo** — mostrar cotizaciones de dólar, euro y monedas de la región tomadas en tiempo real de **forex-erp** (el ERP que digitaliza la casa de cambio), con la tabla `cashes` sembrada como fallback offline. Incluye simulador de operación.
3. **Venta cruzada del ecosistema** — la sección "Ecosistema Kapitalya" vende los demás productos (Forex ERP, Kapitalya Pay, Alerta de Tasas, ProformaPro, Katálogo, Insights) enlazando al Hub.

La API pública `/api/rates` (60 req/min, sin auth) es consumida por `serguicv` para el widget de tasas en vivo y por `exchange-rate-alert-bolivia` para alimentar su motor de alertas, convirtiendo a Tromay en el feed de datos FX del ecosistema.

> **No incluye** (retirado el 2026-07-10): panel admin, autenticación, registro de transacciones compra/venta, gestión de clientes, cotizaciones-proforma, turnos, reportes ni dashboard analítico. La operación transaccional real vive en **forex-erp**.

## Propuesta de valor

| | |
|--|--|
| **Objetivo** | Vitrina pública que posiciona a Tromay como la mejor casa de cambio física de Bolivia y vende el ecosistema |
| **Cómo** | Sitio de marketing Laravel con tasas en vivo desde forex-erp, simulador y sección de ecosistema |
| **Resultado** | Presencia de marca + feed de tasas (`/api/rates`) consumido por serguicv y exchange-alert |

---

## Stack técnico

| Componente | Tecnología |
|------------|-----------|
| Backend | Laravel 9.19 · PHP 8.2 · Eloquent ORM (solo lectura pública, sin auth) |
| Fuente de tasas | forex-erp vía `EXCHANGE_API_URL` (primaria) · tabla `cashes` sembrada (fallback) |
| Frontend | Blade + plantilla marketing Kapitalya · Vite 3 · Vanilla JS · Axios · tema claro/oscuro |
| Base de datos | MySQL 8.0 (`cashes` = tasas fallback, `latests` = noticias) |
| Cache | Redis 7 |
| Servidor | Nginx + PHP-FPM 8.2 Alpine (Supervisor) |
| Build | Node 20 + Vite (multi-stage Docker) |
| Testing | PHPUnit 9.5 (SQLite en memoria) |
| Monitoreo | Sentry (opcional) |

---

## Arquitectura

```
Cloudflare Tunnel
       │
  ingress-nginx (K8s namespace: private)
       │
  tromay_app (PHP-FPM 8.2 + Nginx via Supervisor)
       │              │              │
  forex-erp        MySQL 8.0      Redis 7
  (tasas en vivo,  (cashes=tasas   (caché de
   vía HTTP)        fallback,       tasas 300s)
                    latests=news)

API pública:
  /api/rates  ←── serguicv (widget FX)
  /api/rates  ←── exchange-rate-alert-bolivia (motor alertas)
  RateService ──→ forex-erp /exchange-rates/primary/  (overlay sobre cashes)
  
Health checks K8s:
  /api/health/live   → {"status":"ok","service":"tromay"}
  /api/health/ready  → verifica DB + Cache
```

El Dockerfile usa **build multi-stage** (Node 20 para assets → Composer 2.7 para deps PHP → PHP-FPM 8.2 Alpine final).

---

## Endpoints principales

### API pública (sin autenticación, 60 req/min — definidas en `routes/web.php` bajo prefijo `api`)

| Método | Ruta | Descripción |
|--------|------|-------------|
| `GET` | `/api/rates` | Todas las divisas activas con tasas (overlay forex + fallback DB) |
| `GET` | `/api/rates/{cash}` | Detalle de una divisa (404 si inactiva) |
| `GET` | `/api/rates/{cash}/history` | Histórico de tasas de una divisa |
| `GET` | `/api/calculator` | Cálculo de cotización |
| `GET` | `/api/ext-rates` | Proxy directo a forex-erp (fuente externa) |
| `GET` | `/api/ext-rates/sources/{currency}` | Fuentes disponibles por moneda |
| `POST` | `/api/ext-rates/calculate` | Cálculo con fuentes externas |

### Health checks (sin auth — `routes/api.php`)

| Método | Ruta | Descripción |
|--------|------|-------------|
| `GET` | `/api/health/live` | Liveness probe K8s → `{"status":"ok","service":"tromay"}` |
| `GET` | `/api/health/ready` | Readiness probe (verifica DB + Cache) |

### Sitio público (`routes/web.php`)

| Método | Ruta | Descripción |
|--------|------|-------------|
| `GET` | `/` | Home: hero, tasas en vivo, ecosistema, simulador, noticias |
| `GET` | `/about` · `/contact` · `/privacy` · `/terms` | Páginas institucionales |
| `GET` | `/quote` | Simulador de cotización |
| `GET` | `/dinero/{cash}` | Detalle de divisa |
| `GET` | `/noticia/{latest}` | Detalle de noticia financiera |
| `GET` | `/feed.xml` | RSS de noticias financieras |

> **Sin panel admin ni autenticación.** El registro de transacciones, la gestión de clientes,
> los turnos, reportes y el dashboard se retiraron el 2026-07-10. Toda operación transaccional
> real vive en **forex-erp**; Tromay es solo la vitrina pública.

---

## Estructura del proyecto

```
tromay/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   └── RatesController.php           # API pública de tasas
│   │   │   ├── HomeController.php                # páginas públicas
│   │   │   ├── CashController.php                # /dinero/{cash}
│   │   │   ├── LatestController.php              # /noticia/{latest}
│   │   │   ├── FeedController.php                # RSS
│   │   │   └── ExchangeRateController.php        # proxy forex /api/ext-rates*
│   │   └── Middleware/
│   │       └── SecurityHeaders.php
│   ├── Services/
│   │   └── RateService.php     # tasas: overlay forex + fallback Cash (cache 300s)
│   └── Models/
│       ├── Cash.php            # Divisas (compra, venta, oficial, estado) — fallback de tasas
│       ├── CashRate.php        # Histórico de tasas
│       ├── Latest.php          # Noticias financieras
│       └── User.php            # (queda por config/auth; sin uso — no hay login)
├── database/
│   ├── migrations/             # 8 migraciones principales
│   ├── factories/
│   └── seeders/
├── docker/
│   ├── nginx/                  # nginx.conf + site.conf
│   ├── php/                    # php.ini + opcache.ini + www.conf (max_children=25)
│   └── supervisor/             # supervisord.conf
├── env/
│   ├── .env.dev                # Variables desarrollo
│   ├── .env.prod               # Plantilla producción (sin secretos reales)
│   └── .env.test               # Variables pruebas
├── routes/
│   ├── web.php                 # Rutas públicas (sitio + API de tasas)
│   └── api.php                 # Health checks
├── scripts/
│   ├── setup-dev.bat           # Setup completo DEV (Docker)
│   ├── start-dev.bat / stop-dev.bat
│   ├── setup-test.bat
│   └── deploy-prod.bat
├── tests/
│   ├── Feature/
│   │   ├── RatesApiTest.php    # 6 tests: JSON, activos, 404, headers
│   │   └── PublicPagesTest.php # 8 tests: rutas públicas
│   └── Unit/
├── docker-compose.yml          # Dev/CI base
├── docker-compose.dev.yml      # + Mailpit (8025)
├── docker-compose.prod.yml     # Redis con contraseña, sin debug
├── docker-compose.test.yml     # SQLite en memoria
└── Dockerfile                  # Multi-stage: Node → Composer → PHP-FPM
```

---

## Base de datos

| Tabla | Descripción |
|-------|-------------|
| `cashes` | Divisas con tasas de compra, venta y oficial — **fallback** cuando forex-erp no responde |
| `cash_rates` | Histórico de tasas por divisa |
| `latests` | Noticias y actualizaciones financieras (contenido público) |
| `users` | Legado — sin uso (no hay login) |
| `transactions` / `clients` / `quotations` / `turn_sessions` | **Legado sin uso** — las migraciones se conservan pero los modelos se eliminaron el 2026-07-10 (candidatas a podar) |
| `jobs` / `failed_jobs` | Cola de trabajos asíncronos (Supervisor) |

---

## Variables de entorno

| Variable | Descripción | Requerida |
|----------|-------------|-----------|
| `APP_KEY` | Clave cifrado Laravel (auto-generada) | Sí |
| `APP_URL` | URL base | Sí |
| `DB_HOST` | Host MySQL | Sí |
| `DB_DATABASE` | Nombre base de datos | Sí |
| `DB_USERNAME` | Usuario MySQL | Sí |
| `DB_PASSWORD` | Contraseña MySQL | Sí |
| `CACHE_DRIVER` | Driver de caché (`redis`) | Sí |
| `SESSION_DRIVER` | Driver de sesiones (`redis`) | Sí |
| `REDIS_HOST` | Host Redis | Sí |
| `REDIS_PASSWORD` | Contraseña Redis (prod) | Prod |
| `SESSION_SECURE_COOKIE` | `true` en producción HTTPS | Prod |
| `SENTRY_LARAVEL_DSN` | DSN Sentry para errores | No |

---

## K8s / Despliegue

- **Namespace:** `private` (según el CLAUDE.md raíz del monorepo: exchange-alert, forex-erp, findemproai, findemprolareos, serguicv, tromay)
- **Cluster:** en migración — el K8s de Docker Desktop/Windows (workers `desktop-workerN`) se abandonó el 2026-07-08; el cluster nuevo en Ubuntu Server (k3s/kubeadm) está pendiente de instalar. Hoy corre vía Docker Compose detrás del Cloudflare Tunnel.
- **URL producción:** https://public.kapitalya.com.bo
- **Imagen:** `kapitalya/tromay:vYYYYMMDD` (imagePullPolicy: IfNotPresent)
- **Health checks:** `/api/health/live` (liveness) · `/api/health/ready` (readiness — verifica DB + Cache)

```bash
# Build + import K8s (Ubuntu; el nombre del worker depende del cluster nuevo una vez instalado)
tag="v$(date +%Y%m%d)"
docker build -t kapitalya/tromay:$tag .
docker save kapitalya/tromay:$tag | docker exec -i <nodo-worker> ctr images import -
```

---

## Desarrollo local

```batch
# Setup completo DEV (Docker)
scripts\setup-dev.bat

# Servicios disponibles:
# Aplicación:   http://localhost:8080
# Mailpit:      http://localhost:8025
# MySQL:        localhost:33060 — BD: kapitalya_dev
# Redis:        localhost:6379
```

```bash
# Comandos dentro del contenedor
docker compose -f docker-compose.dev.yml exec app php artisan migrate
docker compose -f docker-compose.dev.yml exec app php artisan db:seed
docker compose -f docker-compose.dev.yml exec app php artisan test
```

---

## Conexiones con otros servicios

| Servicio | Dirección | Uso |
|---------|-----------|-----|
| **serguicv** | consume `/api/rates` | Widget de tasas USD/BOB en portfolio |
| **exchange-rate-alert-bolivia** | consume `/api/rates` | Feed para motor de alertas ARIMA |
| **MySQL** namespace `databases` | `mysql-tromay` (dedicado; en Compose = servicio `tromay_mysql`, DB `kapitalya`) | Base de datos operacional |
| **Redis** namespace `databases` | `redis-shared` (en Compose = servicio `tromay_redis`) | Cache y sesiones |

---

## Seguridad

- `APP_KEY`: fail-fast — Laravel arroja error fatal si no está definida
- **Sin superficie de auth:** sitio 100% público de solo lectura — no hay login, sesiones ni tokens
- **CORS:** no aplica (Blade server-side + API pública GET)
- **Rate limiting API:** 60 solicitudes/minuto por IP
- **Middleware `SecurityHeaders`:** X-Frame-Options, X-Content-Type-Options, Referrer-Policy
- **OPcache:** habilitado en producción (max_accelerated_files=10000)
- **Redis con contraseña** en producción (`docker-compose.prod.yml`)
- `APP_DEBUG=false` en producción (oculta trazas de error)

---

## Integración Hub SSO

Enlazado con [Kapitalya Hub](https://kapitalya.com.bo) — portal central del ecosistema.

El proyecto no implementa `hub_auth` (PHP/Laravel). El acceso desde el Hub es directo via enlace público.

**Frontend:** "← Hub" link agregado en la navegación principal (`resources/views/layouts/master.blade.php` en others-option).

---

*Zona horaria del sistema: `America/La_Paz` (Bolivia)*
