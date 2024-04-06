#Check if key exists
if [ ! -f "/var/www/.env" ]; then
    cp /var/www/.env.prod.example /var/www/.env
    php artisan key:generate
fi

php artisan migrate --force
php artisan optimize

chown -R www-data:www-data /var/www/

#Startup fpm
php-fpm -D

#Startup nginx
nginx -g 'daemon off;'
