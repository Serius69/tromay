# AGENTS.md

## Comandos

- Aplicación Laravel: `php artisan serve`.
- Pruebas PHP: `php artisan test` o `vendor/bin/phpunit`.
- Estilo PHP: `vendor/bin/pint`.
- Recursos Vite: `npm run dev` y `npm run build`.

## Arquitectura y convenciones

- Aplicación Laravel 9: lógica en `app/`, rutas en `routes/`, configuración en `config/`.
- Vistas y fuentes frontend están en `resources/`; recursos servidos, en `public/`.
- Pruebas separadas en `tests/Feature` y `tests/Unit`; clases propias usan PSR-4 bajo `App\\`.
- El sistema visual común vive en `platform/design-system/tromay`: usar `TROMAY_DARK`/`TROMAY_LIGHT`, `data-theme` y `localStorage` con clave `kap-theme`. En fondo claro, usar `--kap-green-ink`, nunca `--kap-green`, para texto.

## Pruebas

- Ejecutar `php artisan test` para cambios PHP y `vendor/bin/pint --test` para estilo.
- Para cambios en recursos ejecutar también `npm run build`.
- No hay suite de pruebas Node declarada en `package.json`.

## Seguridad

- No leer ni imprimir secretos o `.env`; no registrar tokens, credenciales ni datos personales.
- No ejecutar migraciones, seeders ni escrituras en BD. No operar Docker, imágenes, contenedores, k3s o red.
- Mantener validación y autorización del lado servidor y escape de datos en vistas.

## Definición de terminado

- PHPUnit, Pint y build aplicables pasan sin skips ni reducción de calidad.
- El cambio queda limitado al componente pedido y preserva contratos Laravel/Vite.
- No se incluyen secretos, migraciones, datos ni cambios operativos.

## Rutas autorizadas

- Trabajar únicamente dentro de `apps/public/tromay`.

## Restricciones permanentes

- No tocar `apps/core/forex-erp`, `/mnt/E`, `README.md`, `CLAUDE.md` ni `MEMORY.md`.
- No instalar dependencias, acceder a la red, ejecutar migraciones, escribir en BD, reconstruir imágenes, usar `docker compose up/build`, reiniciar contenedores ni tocar k3s.
- No hacer commit, push, cambiar de rama, ampliar alcance ni realizar refactors oportunistas.
