# Church Management System - Installation Guide

## System Requirements

- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer
- Node.js & NPM (optional, for asset compilation)

## Installation Steps

### 1. Install Dependencies

```bash
composer install
```

### 2. Environment Configuration

Copy the `.env.example` file to `.env`:

```bash
copy .env.example .env
```

Edit the `.env` file and configure your database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=churchmang
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 3. Generate Application Key

```bash
php artisan key:generate
```

### 4. Create Database

Create a MySQL database named `churchmang`:

```sql
CREATE DATABASE churchmang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Seed Database

```bash
php artisan db:seed
```

This will create:
- Admin user (admin@church.com / password)
- Manager user (manager@church.com / password)
- Sample data for testing

### 7. Create Storage Link

```bash
php artisan storage:link
```

### 8. Set Permissions (Linux/Mac)

```bash
chmod -R 775 storage bootstrap/cache
```

### 9. Start Development Server

```bash
php artisan serve
```

The application will be available at: http://localhost:8000

## Default Login Credentials

**Admin Account:**
- Email: admin@church.com
- Password: password

**Manager Account:**
- Email: manager@church.com
- Password: password

## Post-Installation

### 1. Change Default Passwords

Immediately change the default passwords after first login.

### 2. Configure SMS Provider

Edit `.env` file and add your SMS provider credentials:

```
SMS_PROVIDER=your_provider
SMS_API_KEY=your_api_key
SMS_SENDER_ID=your_sender_id
```

### 3. Configure Email

Update email settings in `.env`:

```
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourchurch.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Configure Payment Gateway (Optional)

For online donations, configure payment gateway:

```
PAYMENT_GATEWAY_KEY=your_key
PAYMENT_GATEWAY_SECRET=your_secret
```

## Features Overview

### 1. Dashboard
- Real-time statistics
- Attendance trends
- Donation analytics
- Upcoming birthdays
- Recent activities

### 2. Members Management
- Complete member profiles
- Emergency contacts
- Document management
- Status tracking
- Custom fields

### 3. Visitors Tracking
- Visitor registration
- Follow-up management
- Convert to member
- Visit history

### 4. Attendance System
- QR code check-in
- Manual check-in
- Multiple services
- Attendance reports
- Absence notifications

### 5. Financial Management
- Donations tracking
- Pledge management
- Expense tracking
- Budget management
- Financial reports

### 6. Bulk SMS
- Campaign management
- SMS templates
- Scheduled messages
- Delivery reports
- Opt-out management

### 7. Equipment Management
- Equipment tracking
- Assignment management
- Maintenance records
- Category management

### 8. Reports & Analytics
- Membership reports
- Financial reports
- Attendance reports
- Custom date ranges
- Export capabilities

### 9. Cluster Follow-up
- Follow-up tasks
- Activity tracking
- Priority management
- Assignment system

### 10. Settings
- System configuration
- User management
- Role-based access control
- Audit logging

## Security Features

- Role-Based Access Control (RBAC)
- Two-Factor Authentication support
- Password encryption
- CSRF Protection
- XSS Protection
- SQL Injection Prevention
- Session management
- Activity logging
- Audit trails

## Troubleshooting

### Database Connection Error

Check your `.env` database credentials and ensure MySQL is running.

### Permission Denied

Run: `chmod -R 775 storage bootstrap/cache`

### 404 Not Found

Ensure your web server is pointing to the `public` directory.

### Composer Install Fails

Update Composer: `composer self-update`

## Support

For issues and questions, please refer to the documentation or contact support.

## License

MIT License
