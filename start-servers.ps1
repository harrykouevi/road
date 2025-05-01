#!/usr/bin/env pwsh

# Dossier de votre projet user-service
Start-Process powershell -ArgumentList "cd 'C:\www\e\roadmap\user-service'; php artisan serve --host=127.0.0.1 --port=8000"

# Dossier de votre projet traffic-service
Start-Process powershell -ArgumentList "cd 'C:\www\e\roadmap\traffic-service'; php artisan serve --host=127.0.0.1 --port=8001"
