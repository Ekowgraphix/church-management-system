# Church Management System

A comprehensive Church Management System built with Laravel, designed for church administrators to manage members, visitors, attendance, finances, and more.

## Features

- **Dashboard**: Real-time analytics and customizable widgets
- **Member Management**: Complete profile management with lifecycle tracking
- **Visitor Tracking**: Monitor and follow up with church visitors
- **Attendance System**: QR code-based check-in with mobile support
- **Financial Management**: Donations, pledges, expenses, and comprehensive reporting
- **Bulk SMS**: Group messaging with templates and scheduling
- **Equipment Management**: Track church equipment and resources
- **Reports & Analytics**: Comprehensive reporting and data visualization
- **Cluster Follow-up**: Member follow-up and care tracking
- **Security**: Role-based access control, 2FA, and audit logging

## Tech Stack

- **Backend**: PHP Laravel 10
- **Frontend**: HTML5, Tailwind CSS, JavaScript
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **Permissions**: Spatie Laravel Permission
- **PDF Generation**: DomPDF
- **Excel Export**: Maatwebsite Excel
- **QR Codes**: SimpleSoftwareIO QR Code

## Installation

1. Clone the repository
2. Copy `.env.example` to `.env` and configure your database
3. Run `composer install`
4. Run `php artisan key:generate`
5. Run `php artisan migrate --seed`
6. Run `npm install && npm run build`
7. Start the server: `php artisan serve`

## Default Credentials

- **Email**: admin@church.com
- **Password**: password

## Security Features

- Role-Based Access Control (RBAC)
- Two-Factor Authentication
- Data Encryption
- CSRF Protection
- XSS Protection
- SQL Injection Prevention
- Audit Logging

## License

MIT License
