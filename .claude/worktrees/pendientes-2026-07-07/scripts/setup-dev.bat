@echo off
setlocal EnableDelayedExpansion
title Kapitalya - Setup Entorno DEV

echo.
echo  ╔══════════════════════════════════════════════════════════╗
echo  ║         KAPITALYA  —  Setup Entorno DEV                  ║
echo  ║         Base de datos: kapitalya_dev                     ║
echo  ║         URL: http://localhost:8080                       ║
echo  ╚══════════════════════════════════════════════════════════╝
echo.

:: ── Ir al directorio raíz del proyecto ──────────────────────────────
cd /d "%~dp0.."
set "PROJECT_ROOT=%CD%"
echo [INFO] Directorio del proyecto: %PROJECT_ROOT%

:: ── Verificar prerequisitos ──────────────────────────────────────────
echo.
echo [1/8] Verificando prerequisitos...

docker --version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker no esta instalado o no esta en PATH.
    echo         Descarga Docker Desktop desde: https://www.docker.com/products/docker-desktop
    pause & exit /b 1
)

docker info >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker no esta corriendo. Por favor inicia Docker Desktop primero.
    pause & exit /b 1
)

echo [OK] Docker detectado y corriendo.

:: ── Copiar archivo de entorno ────────────────────────────────────────
echo.
echo [2/8] Configurando variables de entorno DEV...

if not exist "env\.env.dev" (
    echo [ERROR] No se encontro: env\.env.dev
    echo         Asegurate de estar en el directorio correcto del proyecto.
    pause & exit /b 1
)

copy /Y "env\.env.dev" ".env" >nul
echo [OK] .env configurado para DEV ^(kapitalya_dev^).

:: ── Detener contenedores anteriores ─────────────────────────────────
echo.
echo [3/8] Deteniendo contenedores previos (si existen)...
docker compose -f docker-compose.dev.yml down --remove-orphans >nul 2>&1
echo [OK] Limpieza completada.

:: ── Construir e iniciar contenedores ────────────────────────────────
echo.
echo [4/8] Construyendo e iniciando contenedores DEV...
echo       (Primera vez puede tardar 3-5 minutos...)
echo.
docker compose -f docker-compose.dev.yml up -d --build
if errorlevel 1 (
    echo [ERROR] Fallo al iniciar contenedores. Revisa el output anterior.
    pause & exit /b 1
)
echo.
echo [OK] Contenedores DEV iniciados.

:: ── Esperar a que MySQL este listo ───────────────────────────────────
echo.
echo [5/8] Esperando a que MySQL este listo...
set RETRY=0
:wait_mysql
set /a RETRY+=1
if %RETRY% GTR 30 (
    echo [ERROR] MySQL no respondio en 60 segundos.
    pause & exit /b 1
)
docker compose -f docker-compose.dev.yml exec -T mysql mysqladmin ping -h localhost --silent >nul 2>&1
if errorlevel 1 (
    timeout /t 2 /nobreak >nul
    goto wait_mysql
)
echo [OK] MySQL listo ^(intento %RETRY%^).

:: ── Generar APP_KEY ──────────────────────────────────────────────────
echo.
echo [6/8] Generando APP_KEY...
docker compose -f docker-compose.dev.yml exec -T app php artisan key:generate --force
if errorlevel 1 (
    echo [WARN] No se pudo generar APP_KEY automaticamente.
    echo        Ejecuta manualmente: php artisan key:generate
)

:: ── Ejecutar migraciones ─────────────────────────────────────────────
echo.
echo [7/8] Ejecutando migraciones...
docker compose -f docker-compose.dev.yml exec -T app php artisan migrate --force
if errorlevel 1 (
    echo [ERROR] Las migraciones fallaron.
    pause & exit /b 1
)
echo [OK] Migraciones completadas.

:: ── Ejecutar seeders ─────────────────────────────────────────────────
echo.
echo [8/8] Ejecutando seeders DEV...
docker compose -f docker-compose.dev.yml exec -T app php artisan db:seed --force
if errorlevel 1 (
    echo [WARN] Los seeders fallaron o no hay seeders definidos.
) else (
    echo [OK] Seeders ejecutados.
)

:: ── Resumen final ────────────────────────────────────────────────────
echo.
echo  ╔══════════════════════════════════════════════════════════╗
echo  ║              ENTORNO DEV LISTO!                          ║
echo  ╠══════════════════════════════════════════════════════════╣
echo  ║  Aplicacion:  http://localhost:8080                      ║
echo  ║  Email UI:    http://localhost:8025  (Mailpit)           ║
echo  ║  MySQL:       localhost:33060  (kapitalya_dev)           ║
echo  ║  Redis:       localhost:6379                             ║
echo  ╠══════════════════════════════════════════════════════════╣
echo  ║  Para detener:  scripts\stop-dev.bat                     ║
echo  ║  Para iniciar:  scripts\start-dev.bat                    ║
echo  ║  Ver logs:      docker compose -f docker-compose.dev.yml logs -f
echo  ╚══════════════════════════════════════════════════════════╝
echo.

pause
