until nc -z -v -w30 todosdb 3306; do
  echo "Waiting for database connection..."
  sleep 10
done

composer install
php artisan config:cache
php artisan migrate:fresh
php artisan key:generate
php artisan passport:install
chown 1000.1000 storage/*
chmod 775 /var/www/storage/logs
php artisan storage:link

php artisan config:cache
php artisan cache:clear
php artisan optimize:clear
