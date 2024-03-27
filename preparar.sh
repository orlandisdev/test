php artisan cache:clear
php artisan route:cache
php artisan config:cache
php artisan view:cache
php artisan event:cache
php artisan optimize
composer dumpautoload -o
#sudo chown apache:apache storage -R
sudo chown www-data:www-data storage -R
rm bootstrap/cache/routes-v7.php
