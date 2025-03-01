# Manufacturing Work Order Management System

## Project Overview

This is a Laravel-based Web Application for Managing Manufacturing Work Orders with Role-Based Access Control (RBAC).

## Prerequisites

- PHP 8.0.2+
- Composer
- Node.js and npm
- MySQL or PostgreSQL

## Installation Steps

### 1. Clone the Repository

```bash
git clone https://github.com/ashafizullah/tspfittings-adamsuchihafizullah-codingtest
cd tspfittings-adamsuchihafizullah-codingtest
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install JavaScript Dependencies

```bash
npm install
```

### 4. Create Environment Configuration

```bash
cp .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Configure Database

Edit the `.env` file with your database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

### 7. Run Migrations and Seeders

```bash
php artisan migrate:fresh --seed
```

### 8. Create Storage Symlink

```bash
php artisan storage:link
```

### 9. Compile Frontend Assets

```bash
npm run dev
```

### 10. Start the Development Server

```bash
php artisan serve
```

## Running the Application

- Backend Server: `php artisan serve` (typically runs at http://localhost:8000)
- Frontend Assets: `npm run dev`

## User Roles

1. **Production Manager**
   - Create work orders
   - Assign operators
   - Update work order status
   - View reports

2. **Operator**
   - View assigned work orders
   - Update work order status
   - Record production progress

## Key Features

- Automatic Work Order Number Generation
- Status Tracking
- Progress Logging
- Role-Based Access Control
- Comprehensive Reporting

## Technology Stack

- Backend: Laravel
- Frontend: Vue 3 with Inertia.js
- Database: MySQL/PostgreSQL
- Authentication: Laravel Fortify
- Permissions: Spatie Laravel-Permission

## Additional Configuration

### Environment Variables

Refer to `.env.example` for all configurable environment variables.

## Troubleshooting

- Ensure all dependencies are installed
- Check database connection
- Verify file permissions
- Run `php artisan config:clear` and `php artisan cache:clear` if experiencing issues

## Security

- Input validation implemented
- Role-based access control
- Password hashing

## Contact

For any inquiries, please contact: adamsuchihafizullah@gmail.com
