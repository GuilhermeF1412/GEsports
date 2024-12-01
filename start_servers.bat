@echo off
echo Starting Laravel and FastAPI servers...

:: Start Laravel Server
start cmd /k "cd %~dp0 && php artisan serve"

:: Start FastAPI Server
start cmd /k "cd %~dp0GEsportsApi && uvicorn main:app --reload --port 8001"

echo Servers are starting in separate windows.
echo Laravel: http://localhost:8000
echo FastAPI: http://localhost:8001
pause 