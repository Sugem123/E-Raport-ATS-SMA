@echo off
title e-Raport SMAN 67 Tangerang
cd /D "%~dp0"

echo ========================================================
echo   Sistem Informasi Akademik e-Raport SMAN 67 Tangerang
echo ========================================================
echo.

:: 1. Cek port 3306 (MySQL)
netstat -ano | findstr :3306 >nul
if %errorlevel% neq 0 (
    echo [*] MySQL belum aktif, menyalakan MySQL XAMPP...
    if exist "C:\xampp\mysql\bin\mysqld.exe" (
        start "" /B "C:\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\xampp\mysql\bin\my.ini" --standalone
        timeout /t 2 /nobreak >nul
    )
) else (
    echo [*] MySQL aktif di port 3306.
)

:: 2. Buka browser otomatis
start http://localhost:8000/login

:: 3. Jalankan PHP Built-in Server
echo [*] Server berjalan di http://localhost:8000 ...
echo [*] Tekan Ctrl+C untuk menghentikan server.
echo.

if exist "C:\xampp\php\php.exe" (
    "C:\xampp\php\php.exe" -S [::]:8000 router.php
) else (
    php -S [::]:8000 router.php
)
pause
