# Kapitalya

Sistema web de gestión de casa de cambio de divisas, construido con Laravel 9. Permite administrar cotizaciones, transacciones de compra/venta, clientes y noticias financieras, con una API pública de tasas de cambio y un panel de administración completo.

---

## Tabla de contenidos

- [Características](#características)
- [Stack tecnológico](#stack-tecnológico)
- [Requisitos previos](#requisitos-previos)
- [Instalación y configuración](#instalación-y-configuración)
  - [Entorno de desarrollo](#entorno-de-desarrollo)
  - [Entorno de pruebas](#entorno-de-pruebas)
  - [Despliegue en producción](#despliegue-en-producción)
- [Variables de entorno](#variables-de-entorno)
- [Uso](#uso)
  - [Comandos disponibles](#comandos-disponibles)
- [API pública](#api-pública)
- [Rutas del sistema](#rutas-del-sistema)
- [Estructura del proyecto](#estructura-del-proyecto)
- [Base de datos](#base-de-datos)
- [Pruebas](#pruebas)
- [Docker](#docker)
- [Seguridad](#seguridad)
- [Contribuciones](#contribuciones)
- [Licencia](#licencia)

---

## Características

- **Sitio público** con cotizaciones en tiempo real, noticias financieras y calculadora de cambio
- **Panel de administración** protegido por autenticación para gestión completa del negocio
- **API REST** pública de tasas de cambio con rate limiting (60 req/min)
- **Gestión de divisas** (compra, venta, tasa oficial, estado activo/inactivo)
- **Gestión de clientes** con datos de identificación
- **Registro de transacciones** de compra y venta con historial completo
- **Módulo de noticias** financieras con editor de contenido
- **Dashboard analítico** con métricas del negocio
- **Soporte Docker** completo para dev, test y producción
- **Cabeceras de seguridad** HTTP configuradas mediante middleware
- **Monitoreo** con integración opcional a Sentry
- **Correos transaccionales** capturados en dev mediante Mailpit

---

## Stack tecnológico

| Capa | Tecnología |
|------|-----------|
| **Lenguaje** | PHP 8.0.2+ |
| **Framework** | Laravel 9.19 |
| **Autenticación** | Laravel Sanctum 3.0 |
| **ORM** | Eloquent |
| **Base de datos** | MySQL 8.0 |
| **Caché / Sesiones** | Redis 7 |
| **Frontend build** | Vite 3.0 |
| **CSS** | PostCSS 8 |
| **JS** | Vanilla JS + Axios |
| **Tablas CRUD** | Yajra DataTables 10 |
| **Plantillas** | Blade (Laravel) |
| **Contenedores** | Docker + Docker Compose |
| **Web server** | Nginx |
| **PHP runtime** | PHP-FPM 8.2 Alpine |
| **Proceso manager** | Supervisor |
| **Testing** | PHPUnit 9.5 |
| **Mail dev** | Mailpit |
| **Monitoreo** | Sentry (opcional) |

---

## Requisitos previos

- [Docker Desktop](https://www.docker.com/products/docker-desktop) instalado y en ejecución
- Windows 10/11 (los scripts de automatización son `.bat`)
- Git

> Para desarrollo sin Docker se requiere PHP 8.0.2+, Composer 2, Node.js 18+, MySQL 8.0 y Redis 7.

---

## Instalación y configuración

### Entorno de desarrollo

```batch
scripts\setup-dev.bat
```

El script ejecuta automáticamente los pasos:

| Paso | Acción |
|------|--------|
| 1 | Verifica que Docker esté instalado y corriendo |
| 2 | Copia `env\.env.dev` → `.env` |
| 3 | Detiene y elimina contenedores previos |
| 4 | Construye e inicia contenedores (`docker-compose.dev.yml`) |
| 5 | Espera confirmación de salud de MySQL |
| 6 | Genera `APP_KEY` automáticamente |
| 7 | Ejecuta migraciones de base de datos |
| 8 | Ejecuta seeders con datos de ejemplo |

Una vez completado, los servicios están disponibles en:

| Servicio | URL |
|----------|-----|
| Aplicación | http://localhost:8080 |
| Mailpit (correos) | http://localhost:8025 |
| MySQL | `localhost:33060` — BD: `kapitalya_dev` |
| Redis | `localhost:6379` |

Para iniciar/detener sin reconstruir:

```batch
scripts\start-dev.bat
scripts\stop-dev.bat
```

Ver logs en tiempo real:

```batch
docker compose -f docker-compose.dev.yml logs -f
```

### Entorno de pruebas

```batch
scripts\setup-test.bat
```

Utiliza `env\.env.test` con SQLite en memoria para mayor velocidad. La base de datos se recrea en cada ejecución de la suite.

### Despliegue en producción

1. Copiar y editar el archivo de entorno de producción, reemplazando todos los valores `CHANGE_ME_*`:

```batch
copy env\.env.prod .env
notepad .env
```

2. Ejecutar el script de despliegue:

```batch
scripts\deploy-prod.bat
```

El script construye la imagen con el `Dockerfile` multi-stage (compilación de assets Node → dependencias Composer → imagen final PHP-FPM + Nginx) y aplica optimizaciones de producción.

---

## Variables de entorno

Los archivos de entorno se encuentran en la carpeta `env/` y **no deben contener secretos reales** al commitearse.

| Variable | Descripción | Ejemplo dev |
|----------|-------------|-------------|
| `APP_NAME` | Nombre de la aplicación | `"Kapitalya [DEV]"` |
| `APP_ENV` | Entorno (`local` / `production`) | `local` |
| `APP_KEY` | Clave de cifrado Laravel (auto-generada) | — |
| `APP_DEBUG` | Modo debug | `true` |
| `APP_URL` | URL base de la aplicación | `http://localhost:8080` |
| `DB_HOST` | Host de MySQL | `mysql` |
| `DB_DATABASE` | Nombre de la base de datos | `kapitalya_dev` |
| `DB_USERNAME` | Usuario de MySQL | `kapitalya_dev` |
| `DB_PASSWORD` | Contraseña de MySQL | `dev_secret` |
| `CACHE_DRIVER` | Driver de caché | `redis` |
| `SESSION_DRIVER` | Driver de sesiones | `redis` |
| `REDIS_HOST` | Host de Redis | `redis` |
| `REDIS_PASSWORD` | Contraseña Redis (null en dev) | `null` |
| `MAIL_HOST` | Host SMTP | `mailpit` |
| `MAIL_PORT` | Puerto SMTP | `1025` |
| `SENTRY_LARAVEL_DSN` | DSN de Sentry (opcional) | — |
| `SESSION_SECURE_COOKIE` | Cookie segura HTTPS (solo prod) | `true` |

---

## Uso

### Comandos disponibles

Ejecutar comandos dentro del contenedor:

```bash
# Migraciones
docker compose -f docker-compose.dev.yml exec app php artisan migrate

# Rollback de migraciones
docker compose -f docker-compose.dev.yml exec app php artisan migrate:rollback

# Seeders
docker compose -f docker-compose.dev.yml exec app php artisan db:seed

# Limpiar caché
docker compose -f docker-compose.dev.yml exec app php artisan cache:clear
docker compose -f docker-compose.dev.yml exec app php artisan config:clear
docker compose -f docker-compose.dev.yml exec app php artisan view:clear

# Tinker (REPL interactivo)
docker compose -f docker-compose.dev.yml exec app php artisan tinker
```

---

## API pública

La API está disponible en `/api` y no requiere autenticación. Tiene un límite de **60 solicitudes por minuto** por IP.

### Obtener todas las divisas activas

```http
GET /api/rates
```

**Respuesta:**

```json
[
  {
    "id": 1,
    "name": "Dólar Estadounidense",
    "buy_rate": "6.96",
    "sell_rate": "7.00",
    "official_rate": "6.97",
    "status": 1
  }
]
```

### Obtener una divisa por ID

```http
GET /api/rates/{id}
```

Devuelve `404` si la divisa no existe o está inactiva.

---

## Rutas del sistema

### Públicas

| Método | Ruta | Descripción |
|--------|------|-------------|
| `GET` | `/` | Página de inicio |
| `GET` | `/about` | Acerca de |
| `GET` | `/contact` | Contacto |
| `GET` | `/privacy` | Política de privacidad |
| `GET` | `/terms` | Términos de servicio |
| `GET` | `/quote` | Calculadora de cotización |
| `GET` | `/dinero/{cash}` | Detalle de divisa |
| `GET` | `/noticia/{latest}` | Detalle de noticia |

### Administración (requiere autenticación)

| Método | Ruta | Descripción |
|--------|------|-------------|
| `GET` | `/admin` | Panel principal |
| `GET` | `/admin/analytics` | Dashboard analítico |
| `GET` | `/admin/buy` | Formulario de compra |
| `GET` | `/admin/sell` | Formulario de venta |
| `POST` | `/admin/transaction/store` | Registrar transacción |
| `*` | `/admin/cash` | CRUD de divisas |
| `*` | `/admin/client` | CRUD de clientes |
| `*` | `/admin/transaction` | CRUD de transacciones |
| `*` | `/admin/latest` | CRUD de noticias |
| `*` | `/admin/quotation` | CRUD de cotizaciones |

---

## Estructura del proyecto

```
kapitalya/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/           # RatesController (API pública)
│   │   │   ├── Crud/          # CashCRUD, ClientCRUD, TransactionCRUD, LatestCRUD, QuotationCRUD
│   │   │   ├── DashboardController.php
│   │   │   ├── HomeController.php
│   │   │   ├── CashController.php
│   │   │   ├── LatestController.php
│   │   │   └── TransactionController.php
│   │   └── Middleware/
│   │       └── SecurityHeaders.php
│   └── Models/
│       ├── Cash.php            # Divisas
│       ├── Client.php          # Clientes
│       ├── Transaction.php     # Transacciones
│       ├── Latest.php          # Noticias
│       └── User.php            # Usuarios admin
├── database/
│   ├── migrations/             # 8 migraciones (users, cash, clients, latests, transactions...)
│   ├── factories/
│   └── seeders/
├── docker/
│   ├── nginx/                  # Configuración Nginx
│   ├── php/                    # PHP-FPM config + OPcache
│   └── supervisor/             # Supervisor para queues
├── env/
│   ├── .env.dev                # Variables de entorno desarrollo
│   ├── .env.prod               # Variables de entorno producción (sin secretos reales)
│   └── .env.test               # Variables de entorno pruebas
├── resources/
│   ├── views/                  # Plantillas Blade
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php                 # Rutas web (públicas + admin)
│   └── api.php                 # Rutas API base
├── scripts/
│   ├── setup-dev.bat           # Setup completo del entorno DEV
│   ├── start-dev.bat           # Iniciar contenedores DEV
│   ├── stop-dev.bat            # Detener contenedores DEV
│   ├── setup-test.bat          # Setup entorno TEST
│   └── deploy-prod.bat         # Despliegue en producción
├── tests/
│   ├── Feature/
│   │   ├── RatesApiTest.php    # 6 tests de la API de tasas
│   │   └── PublicPagesTest.php # 8 tests de páginas públicas
│   └── Unit/
├── docker-compose.yml          # Compose base (dev/CI)
├── docker-compose.dev.yml      # Compose con Mailpit
├── docker-compose.prod.yml     # Compose producción
├── docker-compose.test.yml     # Compose pruebas
├── Dockerfile                  # Build multi-stage
└── vite.config.js              # Configuración Vite
```

---

## Base de datos

El sistema utiliza **MySQL 8.0** con las siguientes tablas principales:

| Tabla | Descripción |
|-------|-------------|
| `users` | Usuarios del sistema de administración |
| `cash` | Divisas con tasas de compra, venta y oficial |
| `clients` | Clientes registrados (CI, nombre, apellido, estado) |
| `transactions` | Transacciones de compra/venta con usuario, cliente y monto |
| `latests` | Noticias y actualizaciones financieras |
| `personal_access_tokens` | Tokens Sanctum |
| `jobs` | Cola de trabajos asíncronos |
| `failed_jobs` | Trabajos fallidos para reintentos |

### Ejecutar migraciones frescas con datos de ejemplo

```bash
docker compose -f docker-compose.dev.yml exec app php artisan migrate:fresh --seed
```

---

## Pruebas

Las pruebas utilizan **SQLite en memoria** para aislamiento y velocidad máxima.

```bash
# Ejecutar toda la suite
docker compose -f docker-compose.dev.yml exec app php artisan test

# Ejecutar archivo específico
docker compose -f docker-compose.dev.yml exec app php artisan test --filter RatesApiTest

# Con PHPUnit directamente
docker compose -f docker-compose.dev.yml exec app ./vendor/bin/phpunit
```

### Cobertura de pruebas

| Archivo | Tests | Descripción |
|---------|-------|-------------|
| `RatesApiTest.php` | 6 | Estructura JSON, filtrado de activos, manejo 404, cabeceras de seguridad |
| `PublicPagesTest.php` | 8 | Todas las rutas públicas, páginas de detalle, respuestas 404 |

---

## Docker

El proyecto incluye configuraciones Docker para cada entorno:

| Archivo | Entorno | Extras |
|---------|---------|--------|
| `docker-compose.yml` | Dev / CI base | App, MySQL, Redis |
| `docker-compose.dev.yml` | Desarrollo local | + Mailpit (puerto 8025) |
| `docker-compose.prod.yml` | Producción | Redis con contraseña, sin debug |
| `docker-compose.test.yml` | Pruebas automatizadas | SQLite en memoria |

### Imagen Docker

El `Dockerfile` usa un **build multi-stage**:

1. **Stage Node** — compila assets con Vite (`npm run build`)
2. **Stage Composer** — instala dependencias PHP sin dev-dependencies
3. **Stage final** — imagen PHP-FPM 8.2 Alpine con Nginx y Supervisor

```bash
# Construir imagen manualmente
docker build -t kapitalya:latest .

# Ver logs de un servicio
docker compose -f docker-compose.dev.yml logs -f app
docker compose -f docker-compose.dev.yml logs -f mysql
```

---

## Seguridad

- **Middleware `SecurityHeaders`** — agrega cabeceras HTTP de seguridad en todas las respuestas
- **Rate limiting** en la API: 60 solicitudes por minuto por IP
- **Laravel Sanctum** para autenticación de la API y sesiones
- **Cookies de sesión** configuradas con `HttpOnly` y `SameSite`
- **`APP_DEBUG=false`** en producción (oculta trazas de error)
- **OPcache** habilitado en producción para rendimiento y seguridad
- **Redis con contraseña** en producción
- **Sentry DSN** opcional para monitoreo de errores en tiempo real (recomendado para fintech)

> **Importante:** Nunca subir al repositorio archivos `.env` con secretos reales. Los archivos en `env/` solo deben contener valores de ejemplo o plantillas con `CHANGE_ME_*`.

---

## Contribuciones

1. Fork del repositorio
2. Crea una rama con tu feature: `git checkout -b feature/nueva-funcionalidad`
3. Ejecuta las pruebas antes de hacer commit: `php artisan test`
4. Aplica el estilo de código del proyecto: `./vendor/bin/pint`
5. Abre un Pull Request describiendo los cambios

---

## Licencia

Este proyecto está bajo la licencia [MIT](https://opensource.org/licenses/MIT).

---

*Zona horaria del sistema: `America/La_Paz` (Bolivia)*
