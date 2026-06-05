<p align="center">
    <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel 12">
    <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php" alt="PHP 8.2">
    <img src="https://img.shields.io/badge/Tailwind_CSS-3-06B6D4?style=for-the-badge&logo=tailwind-css" alt="Tailwind CSS">
    <img src="https://img.shields.io/badge/Alpine.js-3-8BC0D0?style=for-the-badge&logo=alpine.js" alt="Alpine.js">
    <img src="https://img.shields.io/badge/Chart.js-4-FF6384?style=for-the-badge&logo=chart.js" alt="Chart.js">
</p>

# BungeStay - Smart Lodge Management System

A production-grade hotel/lodge management system built with Laravel 12. Manages bookings, payments, check-in/out, payroll, audit trails, and reporting for multi-role staff.

## Features

### Core
- **Room Management** — CRUD, categories, pricing, availability calendar
- **Booking System** — date-based booking, walk-in support, cancellation
- **Payments** — multiple payment methods, receipts (PDF), verification workflow
- **Check-in / Check-out** — guest tracking with timestamps
- **Reviews & Ratings** — guest feedback with admin approval
- **Services** — add-on services for bookings

### Staff & Roles
- **Admin** — full system control, user management, payroll, audit logs, all reports
- **Manager** — business reports, occupancy, activity log, review moderation
- **Receptionist** — walk-in bookings, approve/reject, check-in/out

### Reports & Analytics
- Financial summary, booking stats, room performance, revenue by type
- Interactive charts (Chart.js) — line, bar, pie
- Export to **PDF** (DomPDF) and **CSV**

### Payroll
- Auto-generate monthly payroll per employee role
- Approve / mark-as-paid workflow
- Bonus & deductions per record
- **Payslip PDF** download
- Employee bank / mobile-money / card detail registration

### Audit Trail
- Polymorphic activity logging for all major actions
- Track user, IP, user agent, old/new values
- Admin: full visibility with module/action/search filters
- Manager: business-scoped view (booking, payment, check-in, review)

### User Features
- Wishlist / favorite rooms
- Booking history & receipts
- Profile management
- Notification center

## Tech Stack

| Layer | Stack |
|-------|-------|
| Backend | PHP 8.2, Laravel 12 |
| Frontend | Blade, Alpine.js, Tailwind CSS 3 |
| Charts | Chart.js 4 |
| PDF | DomPDF (Barryvdh) |
| Build | Vite |
| Database | MySQL / MariaDB |

## Requirements

- PHP 8.2+
- Composer 2.x
- MySQL 8.0+ or MariaDB 10.6+
- Node.js 20+ & npm

## Installation

```bash
# Clone the repository
git clone git@github.com:bamugileki/SMART-LODGE-SYSTEM.git
cd SMART-LODGE-SYSTEM

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure database in .env then run:
php artisan migrate --seed

# Build frontend
npm run build
```

## Default Roles

| Role | Slug | Description |
|------|------|-------------|
| Admin | `admin` | Full system access |
| Manager | `manager` | Business operations & reports |
| Receptionist | `receptionist` | Front desk operations |
| Guest | `guest` | Standard user |

## Default Login

After seeding, default credentials are created. Check `DatabaseSeeder.php` for the specific credentials.

## Development

```bash
# Start dev server
php artisan serve

# Watch frontend
npm run dev
```

## License

MIT
