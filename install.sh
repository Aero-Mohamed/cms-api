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



