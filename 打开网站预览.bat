@echo off
chcp 65001 >nul
title AIRPORTCHA 本地网站预览启动器
cd /d "%~dp0"

if not exist "dist\index.html" (
  echo [AIRPORTCHA] 未检测到 dist\index.html，正在执行首次构建...
  call npm run build
)

powershell -NoProfile -Command "try { $r = Invoke-WebRequest -Uri 'http://127.0.0.1:4173/' -UseBasicParsing -TimeoutSec 1; exit 0 } catch { exit 1 }"
if %errorlevel%==0 (
  echo [AIRPORTCHA] 预览服务器已在运行，正在打开浏览器...
  start "" "http://127.0.0.1:4173/"
  exit /b 0
)

echo [AIRPORTCHA] 正在启动本地 HTTP 预览服务器 (http://127.0.0.1:4173/)...
start "AIRPORTCHA Static Preview" /min cmd /c "npm run preview"
timeout /t 2 /nobreak >nul
start "" "http://127.0.0.1:4173/"
exit /b 0
