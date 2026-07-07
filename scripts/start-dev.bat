@echo off
title Kapitalya - Iniciar DEV

echo.
echo  ╔══════════════════════════════════════════════════════════╗
echo  ║         KAPITALYA  —  Iniciar Entorno DEV                ║
echo  ╚══════════════════════════════════════════════════════════╝
echo.

cd /d "%~dp0.."

docker info >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker no esta corriendo. Inicia Docker Desktop primero.
    pause & exit /b 1
)

echo [INFO] Iniciando contenedores DEV...
docker compose -f docker-compose.dev.yml up -d
if errorlevel 1 (
    echo [ERROR] Fallo al iniciar. Intenta con setup-dev.bat para un setup completo.
    pause & exit /b 1
)

echo.
echo  ╔══════════════════════════════════════════════════════════╗
echo  ║  Aplicacion:  http://localhost:8080                      ║
echo  ║  Email UI:    http://localhost:8025  (Mailpit)           ║
echo  ║  MySQL:       localhost:33060  (kapitalya_dev)           ║
echo  ╚══════════════════════════════════════════════════════════╝
echo.

pause
