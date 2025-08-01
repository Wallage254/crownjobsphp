#!/bin/bash

echo "🚀 Starting CrownOpportunities PHP Laravel Application..."
echo "=================================================="

# Stop any running Node.js processes
pkill -f "tsx server/index.ts" 2>/dev/null || true
pkill -f "node.*server" 2>/dev/null || true

# Navigate to Laravel directory
cd laravel-app

# Clear caches
echo "⚡ Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Run migrations and seed data
echo "📦 Setting up database..."
php artisan migrate:fresh --seed --force

# Start Laravel server
echo "🌟 Starting PHP Laravel server on port 5000..."
echo "Visit: http://localhost:5000"
echo ""
echo "Available pages:"
echo "  🏠 Homepage: http://localhost:5000"
echo "  💼 Jobs: http://localhost:5000/jobs"
echo "  👨‍💼 Admin: http://localhost:5000/admin"
echo ""
echo "Press Ctrl+C to stop the server"
echo "=================================================="

php artisan serve --host=0.0.0.0 --port=5000