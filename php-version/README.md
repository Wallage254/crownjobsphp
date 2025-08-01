# CrownOpportunities PHP Version

This is a complete PHP conversion of the CrownOpportunities job board application for production use with hosafrica.

## Features
- Complete job board functionality
- Admin panel for job management
- User applications with file uploads
- Testimonials system
- Contact form
- Responsive design matching the original
- PostgreSQL database integration

## File Structure
- `config/` - Database and configuration files
- `admin/` - Admin panel pages
- `api/` - API endpoints
- `uploads/` - File upload directory
- `assets/` - CSS, JS, and images
- `includes/` - Shared PHP functions
- Database schema provided as SQL file

## Installation
1. Upload files to your web server
2. Configure database connection in config/database.php
3. Run the database.sql file to create tables
4. Set proper permissions on uploads/ directory (755)
5. Configure admin credentials in admin/login.php