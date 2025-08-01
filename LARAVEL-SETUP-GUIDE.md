# CrownOpportunities Laravel Setup Guide

## Running the Laravel Application Independently

Your Laravel application is now **100% PHP** and can run completely independently without Node.js. Here's how to set up and run your server:

### Quick Start

1. **Run the standalone script:**
   ```bash
   ./run-laravel-standalone.sh
   ```

2. **Or manually start the server:**
   ```bash
   cd laravel-app
   php artisan serve --host=0.0.0.0 --port=5000
   ```

3. **Access your application:**
   - Website: http://localhost:5000
   - Admin Panel: http://localhost:5000/admin

### Server Setup Commands

```bash
# Clear Laravel caches
cd laravel-app
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Check database status
php artisan migrate:status

# Start the server
php artisan serve --host=0.0.0.0 --port=5000
```

### Database Management

```bash
# Run migrations
php artisan migrate

# Seed with sample data
php artisan db:seed

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

### Features Available

✅ **Homepage** - Job listings with search and filters
✅ **Job Details** - Individual job pages with application forms
✅ **Job Applications** - CV upload and personal information forms
✅ **Admin Dashboard** - Manage jobs, applications, and testimonials
✅ **Contact Form** - Working contact system
✅ **Testimonials** - Success stories from users
✅ **Categories** - Job filtering by industry

### File Locations

- **Main Application**: `laravel-app/`
- **Views**: `laravel-app/resources/views/`
- **Controllers**: `laravel-app/app/Http/Controllers/`
- **Models**: `laravel-app/app/Models/`
- **Routes**: `laravel-app/routes/web.php`
- **Database**: `laravel-app/database/`

### Production Deployment

For production, use:
```bash
# Optimize for production
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set environment
APP_ENV=production
APP_DEBUG=false
```

The Laravel application now runs entirely on PHP with PostgreSQL database and requires no Node.js components.