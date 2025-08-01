#!/bin/bash

echo "🚀 Starting CrownOpportunities Laravel Application..."
echo "=============================================="

# Kill any existing processes
echo "Stopping any existing servers..."
pkill -f "tsx server/index.ts" 2>/dev/null || true
pkill -f "php artisan serve" 2>/dev/null || true
pkill -f "node.*dev" 2>/dev/null || true

# Navigate to Laravel directory
cd laravel-app

# Clear Laravel caches
echo "Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Check database connection
echo "Checking database connection..."
php artisan migrate:status

# Start Laravel server
echo "Starting Laravel server on http://localhost:5000..."
echo "Press Ctrl+C to stop the server"
echo "=============================================="

php artisan serve --host=0.0.0.0 --port=5000