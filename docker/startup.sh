#Check if key exists
if [ ! -f "/var/www/env/.env" ]; then
    echo "Creating .env file"
    cp /var/www/.env.prod.example /var/www/env/.env
    php artisan key:generate
fi

php artisan migrate --force
php artisan optimize

php artisan prod:enlightn

chown -R www-data:www-data /var/www/

#Startup fpm
php-fpm -D

#Startup nginx
nginx -g 'daemon off;'
