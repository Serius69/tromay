# Changelog — Tromay

## [1.1.0] — 2026-07-07

### Seguridad (crítico)

- **Login real para el panel admin** (`/login`, `/logout`): antes no existía ninguna ruta de
  autenticación y todo `/admin/*` moría en 500 al redirigir a un `route('login')` inexistente.
  Incluye rate limiting (5 intentos/min por email+IP), regeneración de sesión y botón de
  logout en el navbar.
- **Spread de tasas corregido en `TransactionService`**: compra y venta usaban la columna
  invertida (la casa pagaba la tasa de venta al comprar divisa y cobraba la de compra al
  vender — perdía el spread en cada operación). Previews JS de los formularios alineados.
- **Dinero en `decimal`**: `transactions.amount1/2` → `decimal(15,4)` y
  `cashes.buy/sell/oficial` → `decimal(15,6)` (antes `double`; error de redondeo acumulado).
- **Tests aislados de producción**: `phpunit.xml` fuerza sqlite `:memory:` (antes
  `RefreshDatabase` migraba la BD del `.env`). Migración de índices ahora driver-aware.
- `composer.lock` reparado (sentry faltante) + `composer update` dentro de constraints:
  19 → 4 avisos de seguridad (los 4 restantes requieren Laravel 10+). Dockerfile usa
  `composer install` (builds reproducibles; antes `composer update` en cada build).
- API pública `POST /api/ext-rates/calculate` movida a `routes/api.php` (devolvía 419 CSRF).
- `/health` ya no expone `APP_ENV`.

### Nuevas funciones

- **Cotización → transacción en 1 clic**: `POST admin/quotation/{id}/convert` con tasa
  pactada, bloqueo anti doble-conversión, estado `Convertida` (2) y FK `transaction_id`.
  Las cotizaciones convertidas no se pueden editar.
- **Cierre diario exportable**: botón Excel/CSV (`?export=csv`, BOM UTF-8, separador `;`)
  además del imprimir/PDF existente.
- Sidebar: "Editar Cotizaciones" ahora apunta a `admin/quotation` (+ enlace "Divisas y Tasas").

### Corrección de bugs

- `/api/calculator` devolvía 404 siempre: `firstWhere('name','usd')` comparaba contra el
  accessor `ucwords`. También arreglado `$dollar` (ticker USD) en el sitio público.
- Páginas de error (404/429/500/503) explotaban en 500 por `$cashes` sin definir en el layout.
- Etiquetas Compra/Venta unificadas a perspectiva de la casa (type 2 = compra divisa) en
  listado admin, API datatable y dashboard; el cierre diario y KPIs suman ahora solo el
  lado BOB (antes mezclaban BOB con divisa extranjera).
- Dashboard: caché invalidado al registrar transacciones; `RouteServiceProvider::HOME`
  apuntaba a `/home` (ruta inexistente).

### Revisión multi-agente posterior (misma fecha)

- **Simulador público de `/quote` corregido**: en "Compro" multiplicaba BOB por la tasa
  (unidades imposibles) y usaba la columna de compra; ahora divide por la tasa de venta.
  En "Vendo" aplicaba venta en vez de compra. Fila "inversa" (mezclaba unidades) retirada.
- Migración a decimal normaliza `cashes.oficial` NULL antes del `NOT NULL` (evita fallo
  de deploy con `sql_mode` strict).
- Conversión de cotización valida que la divisa siga activa; cotizaciones convertidas
  tampoco se pueden **eliminar** (respaldo de la tasa pactada) y sus botones se ocultan.
- CSV del cierre: valores que empiezan con `= + - @` van precedidos de apóstrofo
  (anti inyección de fórmulas Excel).
- `/dinero/{cash}` mostraba el ticker USD vacío (mismo bug del accessor `ucwords`).
- `Transaction::type_label` centraliza la etiqueta Compra/Venta (antes 5 copias).
- Dashboard: `summary()` cacheado 300s (aterrizaje post-login pasaba de 7 queries a 0);
  borrar transacciones también invalida el caché; `monthVolumeBoB` en una sola query.
- `TransactionPerformanceSeeder` generaba filas type 1 con la semántica vieja.
- Regla de convertibilidad unificada en `Quotation::convertibilityError()` (UI y servicio
  deciden con la misma fuente). Throttle de login por IP subido a 20/min como backstop
  del límite operativo 5/min por email.

### Tests

- 91 tests / 308 aserciones en verde (antes 52): + auth (7), conversión de cotizaciones (10),
  cierre diario + CSV + anti-inyección (7), turnos de caja (7), CRUD de tasas + observer +
  spread invertido rechazado (6), convertibilidad (4), spread nunca negativo (1).

---

## [1.0.0] — 2026-06-13

### Primer lanzamiento público

**API pública de tasas de cambio**
- Endpoint `/api/rates` con tasas referenciales USD/EUR/CLP/PEN/BRL/ARS vs BOB
- Endpoint `/api/rates/{id}` para consultar una divisa específica
- Historial de tasas `/api/rates/{id}/history` (últimas 60 actualizaciones)
- Calculadora de conversión `/api/calculate` (monto + divisa + tipo buy/sell)
- Cache de 5 minutos via Laravel Cache para reducir carga en DB
- Rate limiting: 60 requests/minuto por IP (throttle middleware)
- Headers de seguridad: `X-Frame-Options`, `X-Content-Type-Options`, sin `X-Powered-By`

**Autenticación y dashboard administrativo**
- Dashboard interno para cajeros y administradores de Tromay Casas de Cambio
- Registro de transacciones con Yajra DataTables
- Registro de clientes y cierres de turno
- Reportes exportables a PDF y Excel

**Infraestructura**
- Laravel 9 / PHP 8.2 / MySQL 8.0
- Desplegado en K8s namespace `private` (acceso via Tailscale)
- URL pública: `tromay.kapitalya.com.bo`
- 11 tests PHPUnit cubriendo API, dashboard y seguridad

---

## [0.9.0] — 2026-05-01

### Beta privada

- Sistema de gestión de transacciones para uso interno en la casa de cambio
- API `/api/rates` básica consumida por serguicv y exchange-alert-bolivia
- Dashboard con DataTables para listado de transacciones y clientes
- Integración con Kapitalya Hub SSO

---

## [0.1.0] — 2025-01-01

### Prototipo inicial

- Aplicación Laravel básica para registro de tasas de cambio
- Vista pública simple con tasas USD/BOB
