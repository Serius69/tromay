# Tromay — Sistema Integral para Casa de Cambio

> Sistema Laravel completo para la operación de casas de cambio bolivianas: gestión de transacciones FX, clientes, tasas en tiempo real y noticias financieras, con API pública de tasas consumida por todo el ecosistema Kapitalya.

**Stack:** Laravel 9 · PHP 8.2 · MySQL 8.0 · Redis 7 · Yajra DataTables · **URL:** tromay.kapitalya.com.bo · **Estado:** ✅ K8s Running

---

## Qué resuelve

Las casas de cambio bolivianas operan con procesos mayoritariamente manuales: registro de transacciones en hojas de cálculo, gestión de clientes sin sistema, tasas actualizadas a mano y sin visibilidad analítica del negocio. Esta operación manual genera errores, pérdida de tiempo y falta de trazabilidad regulatoria.

Tromay digitaliza toda la operación de una casa de cambio: desde el registro de divisas y clientes hasta las transacciones de compra/venta, con un panel administrativo completo y un módulo de noticias financieras. El sistema incluye una calculadora de cotización pública y un dashboard analítico con métricas del negocio.

La API pública `/api/rates` (60 req/min, sin auth) es consumida por `serguicv` para el widget de tasas en vivo y por `exchange-rate-alert-bolivia` para alimentar su motor de alertas, convirtiendo a Tromay en el feed de datos FX del ecosistema.

## Propuesta de valor

| | |
|--|--|
| **Problema** | Casa de cambio con procesos manuales, sin trazabilidad ni analítica |
| **Solución** | Sistema Laravel completo con CRUD transaccional, API pública de tasas y dashboard |
| **Resultado** | Operación digitalizada; feed de tasas consumido por serguicv y exchange-alert |

---

## Stack técnico

| Componente | Tecnología |
|------------|-----------|
| Backend | Laravel 9.19 · PHP 8.2 · Eloquent ORM |
| Autenticación | Laravel Sanctum 3.0 |
| Frontend | Blade · Vite 3 · Vanilla JS · Axios |
| Tablas CRUD | Yajra DataTables 10 (server-side, 100k+ registros) |
| Base de datos | MySQL 8.0 |
| Cache / Sesiones | Redis 7 |
| Servidor | Nginx + PHP-FPM 8.2 Alpine (Supervisor) |
| Build | Node 20 + Vite (multi-stage Docker) |
| Testing | PHPUnit 9.5 (SQLite en memoria) |
| Monitoreo | Sentry (opcional) |

---

## Arquitectura

```
Cloudflare Tunnel
       │
  ingress-nginx (K8s namespace: public)
       │
  tromay_app (PHP-FPM 8.2 + Nginx via Supervisor)
       │                    │
  MySQL 8.0            Redis 7
  (transacciones,      (cache sesiones,
   clientes, tasas)     caché de tasas)

API pública:
  /api/rates  ←── serguicv (widget FX)
  /api/rates  ←── exchange-rate-alert-bolivia (motor alertas)
  
Health checks K8s:
  /api/health/live   → {"status":"ok","service":"tromay"}
  /api/health/ready  → verifica DB + Cache
```

El Dockerfile usa **build multi-stage** (Node 20 para assets → Composer 2.7 para deps PHP → PHP-FPM 8.2 Alpine final).

---

## Endpoints principales

### API pública (sin autenticación, 60 req/min)

| Método | Ruta | Descripción |
|--------|------|-------------|
| `GET` | `/api/rates` | Todas las divisas activas con tasas |
| `GET` | `/api/rates/{id}` | Detalle de una divisa (404 si inactiva) |
| `GET` | `/api/health/live` | Liveness probe K8s |
| `GET` | `/api/health/ready` | Readiness probe (verifica DB + Cache) |
| `GET` | `/api/transactions/datatable` | DataTable server-side (requiere Sanctum) |

### Sitio público

| Método | Ruta | Descripción |
|--------|------|-------------|
| `GET` | `/` | Página de inicio con cotizaciones en tiempo real |
| `GET` | `/quote` | Calculadora de cotización |
| `GET` | `/dinero/{cash}` | Detalle de divisa |
| `GET` | `/noticia/{latest}` | Detalle de noticia financiera |

### Administración (requiere autenticación)

| Ruta | Descripción |
|------|-------------|
| `/admin` | Panel principal |
| `/admin/analytics` | Dashboard analítico con métricas del negocio |
| `/admin/buy` · `/admin/sell` | Formularios de compra y venta |
| `/admin/cash` | CRUD de divisas |
| `/admin/client` | CRUD de clientes (con CI) |
| `/admin/transaction` | CRUD de transacciones completo |
| `/admin/latest` | CRUD de noticias financieras |
| `/admin/quotation` | CRUD de cotizaciones |

---

## Estructura del proyecto

```
tromay/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── RatesController.php           # API pública de tasas
│   │   │   │   └── TransactionDatatableController.php  # DataTable server-side
│   │   │   ├── Crud/                             # CashCRUD, ClientCRUD, TransactionCRUD...
│   │   │   ├── DashboardController.php
│   │   │   ├── HomeController.php
│   │   │   └── TransactionController.php
│   │   └── Middleware/
│   │       └── SecurityHeaders.php
│   └── Models/
│       ├── Cash.php            # Divisas (compra, venta, oficial, estado)
│       ├── Client.php          # Clientes (CI, nombre, apellido)
│       ├── Transaction.php     # Transacciones de compra/venta
│       ├── Latest.php          # Noticias financieras
│       └── User.php
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
│   ├── web.php                 # Rutas públicas + admin
│   └── api.php                 # Health checks + rates + datatable
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
| `cash` | Divisas con tasas de compra, venta y oficial, estado activo/inactivo |
| `clients` | Clientes con CI, nombre, apellido |
| `transactions` | Transacciones compra/venta con usuario, cliente y monto |
| `latests` | Noticias y actualizaciones financieras |
| `users` | Usuarios del panel de administración |
| `jobs` / `failed_jobs` | Cola de trabajos asíncronos (Supervisor) |
| `personal_access_tokens` | Tokens Sanctum para API |

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

- **Namespace:** `public`
- **Worker:** `desktop-worker3`
- **URL producción:** https://tromay.kapitalya.com.bo
- **Imagen:** `kapitalya/tromay:vYYYYMMDD` (imagePullPolicy: IfNotPresent)
- **Health checks:** `/api/health/live` (liveness) · `/api/health/ready` (readiness — verifica DB + Cache)

```powershell
# Build + import K8s (workflow estándar Kapitalya)
docker build -t kapitalya/tromay:v$(Get-Date -Format "yyyyMMdd") .
docker save kapitalya/tromay:vYYYYMMDD -o tromay.tar
docker exec -i desktop-worker3 ctr images import - < tromay.tar
Remove-Item tromay.tar
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
| **MySQL** namespace `databases` | `mysql-lacteos` interno | Base de datos operacional |
| **Redis** namespace `databases` | `redis-shared` | Cache y sesiones |

---

## Seguridad

- `APP_KEY`: fail-fast — Laravel arroja error fatal si no está definida
- **CORS:** no aplica (server-side Blade + API con Sanctum)
- **Rate limiting API:** 60 solicitudes/minuto por IP
- **Laravel Sanctum:** autenticación de la API y sesiones admin
- **Cookies:** `HttpOnly` + `SameSite` + `Secure` en producción
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
