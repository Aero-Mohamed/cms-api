# install.sh
#!/bin/bash

echo "🔧 Run backend initialization..."

composer install --no-interaction --prefer-dist --optimize-autoloader

if [ -f artisan ]; then
    php -r "file_exists('.env') || copy('.env.example', '.env');"
    echo "✅ Copy env file"

    php artisan key:generate
    echo "✅ Generate App Key"

    php artisan migrate:fresh --seed --force
    echo "✅ Migrate Fresh tables and Seeders"

    php artisan passport:keys -n -q
    php artisan passport:client --personal --name="Personal Access Client" --provider=users --no-interaction
    echo "✅ Generate Passport Keys and Personal Access Client"

    php artisan storage:link
    echo "✅ Link the storage"

    php artisan cache:clear
    php artisan route:clear
    echo "✅ Clear Cache"
fi

find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 777 storage bootstrap/cache
chmod 755 init.sh vendor/bin/*
chmod 600 storage/oauth-*.key
chown www-data:www-data /var/www/storage/oauth-*.key

echo "✅ Setup All files and folders permissions"



