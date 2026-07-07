@echo off
title Kapitalya - Detener DEV

echo.
echo  ╔══════════════════════════════════════════════════════════╗
echo  ║         KAPITALYA  —  Detener Entorno DEV                ║
echo  ╚══════════════════════════════════════════════════════════╝
echo.

cd /d "%~dp0.."

echo [INFO] Deteniendo contenedores DEV (datos se conservan)...
docker compose -f docker-compose.dev.yml down
echo [OK] Contenedores detenidos.
echo.
echo  Los datos de MySQL y Redis se conservaron en los volumenes Docker.
echo  Para eliminar TODO incluyendo datos: docker compose -f docker-compose.dev.yml down -v
echo.

pause
