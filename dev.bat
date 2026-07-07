@echo off
chcp 65001 >nul
setlocal

title TROMAY - DEV

cd /d "%~dp0"

echo ============================================================
echo TROMAY - MODO DESARROLLO
echo Laravel : http://localhost:8002
echo Vite    : http://localhost:5175
echo ============================================================
echo.

if not exist ".env" (
    if exist "env\.env.dev" (
        echo [INFO] Copiando .env.dev...
        copy "env\.env.dev" ".env" >nul
    ) else (
        echo [ERROR] .env no encontrado
        pause
        exit /b 1
    )
)

if not exist "vendor\autoload.php" (
    echo [ERROR] Ejecuta composer install
    pause
    exit /b 1
)

if not exist "node_modules" (
    echo [ERROR] Ejecuta npm install
    pause
    exit /b 1
)

echo [1/2] Iniciando Vite...

start "TROMAY VITE" cmd /k "cd /d %~dp0 && node node_modules\vite\bin\vite.js --port 5175"

timeout /t 3 /nobreak >nul

echo [2/2] Iniciando Laravel...

php artisan serve --host=0.0.0.0 --port=8002