until nc -z -v -w30 todosdb 3306; do
  echo "Waiting for database connection..."
  sleep 10
done

composer install
php artisan config:cache
php artisan migrate:fresh
php artisan key:generate
php artisan passport:install
chmod 777 /var/www/storage/logs
chown -R root:www-data /var/www/storage
php artisan storage:link

php artisan config:cache
php artisan cache:clear

chmod -R gu+w storage

chmod -R guo+w storage

php artisan cache:clear