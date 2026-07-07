# Changelog — Tromay

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
