#!/bin/sh
# Arranque del contenedor en producción
set -e

# Render genera APP_KEY como base64 de 32 bytes; Laravel la espera con el prefijo "base64:"
case "$APP_KEY" in
  base64:*) ;;
  "") echo "ERROR: falta la variable APP_KEY" >&2; exit 1 ;;
  *) export APP_KEY="base64:$APP_KEY" ;;
esac

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crea/actualiza las tablas y, solo la primera vez, carga los datos de ejemplo
php artisan migrate --force
php artisan app:sembrar-si-vacia

# Los comandos anteriores corren como root: Apache (www-data) debe poder escribir aquí
chown -R www-data:www-data storage bootstrap/cache

exec apache2-foreground
