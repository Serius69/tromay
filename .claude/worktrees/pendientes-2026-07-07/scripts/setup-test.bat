@echo off
setlocal EnableDelayedExpansion
title Kapitalya - Setup + Tests

echo.
echo  ╔══════════════════════════════════════════════════════════╗
echo  ║         KAPITALYA  —  Entorno TEST                       ║
echo  ║         Base de datos: kapitalya_test (efimera)          ║
echo  ╚══════════════════════════════════════════════════════════╝
echo.

cd /d "%~dp0.."

:: ── Verificar Docker ─────────────────────────────────────────────────
docker info >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker no esta corriendo. Inicia Docker Desktop primero.
    pause & exit /b 1
)

:: ── Copiar env de testing ────────────────────────────────────────────
echo [1/6] Configurando variables de entorno TEST...
if not exist "env\.env.test" (
    echo [ERROR] No se encontro: env\.env.test
    pause & exit /b 1
)
copy /Y "env\.env.test" ".env" >nul
echo [OK] .env configurado para TEST.

:: ── Detener y limpiar contenedores previos ───────────────────────────
echo [2/6] Limpiando entorno TEST anterior...
docker compose -f docker-compose.test.yml down --remove-orphans -v >nul 2>&1
echo [OK] Limpieza completada (base de datos efimera eliminada).

:: ── Iniciar contenedores TEST ────────────────────────────────────────
echo [3/6] Iniciando contenedores TEST...
docker compose -f docker-compose.test.yml up -d --build
if errorlevel 1 (
    echo [ERROR] Fallo al iniciar contenedores TEST.
    pause & exit /b 1
)

:: ── Esperar MySQL ────────────────────────────────────────────────────
echo [4/6] Esperando MySQL TEST...
set RETRY=0
:wait_mysql
set /a RETRY+=1
if %RETRY% GTR 30 (
    echo [ERROR] MySQL TEST no respondio en tiempo.
    pause & exit /b 1
)
docker compose -f docker-compose.test.yml exec -T mysql mysqladmin ping -h localhost --silent >nul 2>&1
if errorlevel 1 (
    timeout /t 2 /nobreak >nul
    goto wait_mysql
)
echo [OK] MySQL TEST listo.

:: ── Generar key y migraciones limpias ───────────────────────────────
echo [5/6] Preparando base de datos TEST (migrate:fresh)...
docker compose -f docker-compose.test.yml exec -T app php artisan key:generate --force >nul
docker compose -f docker-compose.test.yml exec -T app php artisan migrate:fresh --force
if errorlevel 1 (
    echo [ERROR] Fallo migrate:fresh en TEST.
    docker compose -f docker-compose.test.yml down -v >nul 2>&1
    pause & exit /b 1
)
echo [OK] Base de datos TEST preparada.

:: ── Ejecutar suite de tests ──────────────────────────────────────────
echo.
echo [6/6] Ejecutando suite de tests...
echo ────────────────────────────────────────────────────────────
docker compose -f docker-compose.test.yml exec -T app php artisan test --env=testing
set TEST_RESULT=%errorlevel%
echo ────────────────────────────────────────────────────────────

:: ── Limpiar contenedores TEST (dejar el sistema limpio) ──────────────
echo.
echo [INFO] Eliminando contenedores TEST...
docker compose -f docker-compose.test.yml down -v >nul 2>&1

:: ── Resultado ────────────────────────────────────────────────────────
echo.
if %TEST_RESULT% EQU 0 (
    echo  ╔══════════════════════════════════════════════════════════╗
    echo  ║               TODOS LOS TESTS PASARON!                  ║
    echo  ╚══════════════════════════════════════════════════════════╝
) else (
    echo  ╔══════════════════════════════════════════════════════════╗
    echo  ║         HAY TESTS FALLANDO - Revisa el output            ║
    echo  ╚══════════════════════════════════════════════════════════╝
)
echo.

pause
exit /b %TEST_RESULT%
