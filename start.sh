#!/bin/bash

# Railway Database Setup Script
# This script runs automatically on Railway deployment

echo "🚀 Starting Symfony deployment..."

# Set production environment
export APP_ENV=prod
export APP_DEBUG=0

echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "🗄️ Running database migrations..."
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

echo "🧹 Clearing cache..."
php bin/console cache:clear --env=prod --no-debug

echo "🔥 Warming up cache..."
php bin/console cache:warmup --env=prod

echo "✅ Deployment complete!"
echo "🌐 Application is ready to serve requests"

# Start PHP server
exec php -S 0.0.0.0:${PORT:-8000} -t public
