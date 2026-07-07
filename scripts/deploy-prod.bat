@echo off
setlocal EnableDelayedExpansion
title Kapitalya - Deploy PRODUCCION

echo.
echo  ╔══════════════════════════════════════════════════════════════╗
echo  ║         KAPITALYA  —  Deploy PRODUCCION                      ║
echo  ║                                                              ║
echo  ║  ⚠️  ATENCION: Este script es para el servidor VPS.          ║
echo  ║     En desarrollo local usa: scripts\setup-dev.bat          ║
echo  ╚══════════════════════════════════════════════════════════════╝
echo.

cd /d "%~dp0.."

:: ── Verificar que estamos en produccion intencionalmente ─────────────
echo  ¿Estas seguro que quieres desplegar a PRODUCCION?
echo  Escribe YES para continuar o cualquier otra cosa para cancelar:
set /p CONFIRM=  >
if /i NOT "%CONFIRM%"=="YES" (
    echo [INFO] Deploy cancelado.
    pause & exit /b 0
)

:: ── Verificar Docker ─────────────────────────────────────────────────
docker info >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker no esta corriendo.
    pause & exit /b 1
)

:: ── Verificar archivo .env.prod ──────────────────────────────────────
echo.
echo [1/7] Verificando configuracion de produccion...
if not exist "env\.env.prod" (
    echo [ERROR] No se encontro: env\.env.prod
    pause & exit /b 1
)

:: Verificar que los CHANGE_ME fueron reemplazados
findstr /C:"CHANGE_ME" "env\.env.prod" >nul 2>&1
if not errorlevel 1 (
    echo [ERROR] env\.env.prod contiene valores CHANGE_ME sin reemplazar!
    echo         Edita env\.env.prod con los valores reales de produccion.
    pause & exit /b 1
)
echo [OK] Configuracion de produccion verificada.

:: ── Copiar env de produccion ─────────────────────────────────────────
echo [2/7] Aplicando configuracion PROD...
copy /Y "env\.env.prod" ".env" >nul
echo [OK] .env configurado para PRODUCCION.

:: ── Correr tests antes de deploy ─────────────────────────────────────
echo.
echo [3/7] Ejecutando tests antes del deploy...
echo       (Si los tests fallan, el deploy se cancela)
call "%~dp0setup-test.bat" >nul
if errorlevel 1 (
    echo [ERROR] Los tests fallaron. Deploy CANCELADO para proteger produccion.
    pause & exit /b 1
)
:: Restaurar .env de produccion (setup-test.bat lo cambio a test)
copy /Y "env\.env.prod" ".env" >nul
echo [OK] Tests pasaron.

:: ── Build imagen Docker ──────────────────────────────────────────────
echo.
echo [4/7] Construyendo imagen Docker para produccion...
docker build --target production -t kapitalya:prod .
if errorlevel 1 (
    echo [ERROR] Fallo el build Docker.
    pause & exit /b 1
)
echo [OK] Imagen Docker construida.

:: ── Optimizaciones Laravel para produccion ───────────────────────────
echo.
echo [5/7] Iniciando stack de produccion...
docker compose -f docker-compose.prod.yml up -d --build
if errorlevel 1 (
    echo [ERROR] Fallo al iniciar stack de produccion.
    pause & exit /b 1
)

:: Esperar la app
timeout /t 15 /nobreak >nul

echo.
echo [6/7] Ejecutando migraciones y optimizaciones...
docker compose -f docker-compose.prod.yml exec -T app php artisan migrate --force
if errorlevel 1 (
    echo [ERROR] Fallo en migraciones de produccion.
    pause & exit /b 1
)

:: Cache de configuracion, rutas y vistas
docker compose -f docker-compose.prod.yml exec -T app php artisan config:cache
docker compose -f docker-compose.prod.yml exec -T app php artisan route:cache
docker compose -f docker-compose.prod.yml exec -T app php artisan view:cache
echo [OK] Optimizaciones aplicadas.

:: ── Health check ─────────────────────────────────────────────────────
echo.
echo [7/7] Verificando salud del sistema...
set RETRY=0
:health_check
set /a RETRY+=1
if %RETRY% GTR 12 (
    echo [ERROR] Health check fallo. Revisa los logs:
    echo         docker compose -f docker-compose.prod.yml logs app
    pause & exit /b 1
)
docker compose -f docker-compose.prod.yml exec -T app curl -sf http://localhost/api/rates >nul 2>&1
if errorlevel 1 (
    timeout /t 5 /nobreak >nul
    goto health_check
)
echo [OK] Sistema respondiendo correctamente.

:: ── Resultado ────────────────────────────────────────────────────────
echo.
echo  ╔══════════════════════════════════════════════════════════════╗
echo  ║              DEPLOY A PRODUCCION EXITOSO!                    ║
echo  ╠══════════════════════════════════════════════════════════════╣
echo  ║  Ver logs:   docker compose -f docker-compose.prod.yml logs -f
echo  ║  Estado:     docker compose -f docker-compose.prod.yml ps   ║
echo  ╚══════════════════════════════════════════════════════════════╝
echo.

pause
