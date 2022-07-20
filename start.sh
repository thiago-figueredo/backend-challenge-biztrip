composer install &&
cp .env.example .env &&
sudo service mysql start &&
mysql -u root -p -e "CREATE DATABASE laravel;" &&
php artisan migrate &&
php artisan serve --port=3000