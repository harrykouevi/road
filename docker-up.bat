@echo off
SET NETWORK_NAME=shared-net

:: Vérifier si le réseau existe
docker network inspect %NETWORK_NAME% >nul 2>&1
IF %ERRORLEVEL% NEQ 0 (
    echo 🔧 Le réseau "%NETWORK_NAME%" n'existe pas. Création...
    docker network create %NETWORK_NAME%
    echo ✅ Réseau "%NETWORK_NAME%" créé avec succès.
) ELSE (
    echo ✅ Le réseau "%NETWORK_NAME%" existe déjà.
)

:: Exécuter docker-compose avec les arguments passés au script
echo 🚀 Exécution de docker-compose %*
docker-compose %*