#!/bin/bash

echo "🚀 CrownOpportunities Laravel Server"
echo "======================================"

# Clear caches
php artisan config:clear
php artisan cache:clear

# Start server
echo "Server starting at: http://localhost:5000"
php artisan serve --host=0.0.0.0 --port=5000