# BungeStay — Smart Lodge Management System

## Comprehensive System Report

---

# Executive Summary

BungeStay is a production-grade hotel and lodge management system built on **Laravel 12** with **PHP 8.2**, designed to handle the full lifecycle of lodging operations — from room browsing and booking to payment processing, check-in/out, payroll, auditing, and reporting. The system serves four distinct user roles (Admin, Manager, Receptionist, Guest) with role-scoped dashboards, navigation, and permissions. It features real-time availability tracking, interactive reports with Chart.js visualizations, PDF receipt and payslip generation via DomPDF, a complete audit trail, and a payroll management subsystem.

---

# 1. Architecture Overview

## 1.1 Technology Stack

| Layer | Technology |
|---|---|
| **Backend Framework** | Laravel 12 |
| **Language** | PHP 8.2+ |
| **Database** | SQLite (default) / MySQL / MariaDB / PostgreSQL |
| **Frontend** | Blade templating engine |
| **CSS Framework** | Tailwind CSS 3 |
| **Reactive UI** | Alpine.js 3 |
| **Charts** | Chart.js 4 |
| **PDF Generation** | DomPDF (barryvdh/laravel-dompdf) |
| **Build Tool** | Vite 7 |
| **Auth Scaffolding** | Laravel Breeze 2 |

## 1.2 Database Schema — 20 Tables

The system uses 20 database tables across 19 migration files:

### Core Entity Tables
| Table | Purpose |
|---|---|
| `roles` | Role definitions (admin, manager, receptionist, guest) |
| `users` | All users — guests and staff alike (unified user model) |
| `room_types` | Room categories (Single, Double, Suite, Family) |
| `rooms` | Individual rooms with pricing, capacity, amenities, images |
| `services` | Add-on services available for booking |
| `bookings` | Reservation records linking guests to rooms |
| `payments` | Payment transactions against bookings |
| `reviews` | Guest ratings and comments |
| `check_ins` | Physical check-in/check-out records |

### Relationship & Feature Tables
| Table | Purpose |
|---|---|
| `booking_service` | Pivot: services attached to bookings (with quantity & price) |
| `wishlists` | Guest favorite rooms (unique per user+room) |
| `notifications` | Per-user notification records |
| `contact_messages` | Guest inquiries from the contact form |

### System & Settings Tables
| Table | Purpose |
|---|---|
| `settings` | Key-value store for system configuration |
| `sessions` | User session storage |
| `cache` / `cache_locks` | Cache backend |
| `jobs` / `job_batches` / `failed_jobs` | Queue job management |
| `password_reset_tokens` | Password reset flow |

### Payroll & Audit Tables
| Table | Purpose |
|---|---|
| `payrolls` | Monthly employee salary records |
| `employee_bank_details` | Employee payment method information |
| `audit_logs` | Polymorphic activity audit trail |

---

# 2. User Roles & Permissions

## 2.1 Role Definitions

| Role | Slug | Description | Dashboard |
|---|---|---|---|
| **Admin** | `admin` | Full system control | Admin dashboard with all KPIs |
| **Manager** | `manager` | Business operations & reports | Manager dashboard (business view) |
| **Receptionist** | `receptionist` | Front desk operations | Receptionist dashboard (today's focus) |
| **Guest** | `guest` | Standard authenticated user | Guest dashboard (own bookings) |

## 2.2 Role-Based Access Control

The system uses a `RoleMiddleware` that checks the authenticated user's `role.slug` against a whitelist. Routes are protected with `role:slug1,slug2,...` middleware declarations in `routes/web.php`.

### Capability Matrix

| Feature | Guest | Receptionist | Manager | Admin |
|---|---|---|---|---|
| Browse rooms & services | ✓ | ✓ | ✓ | ✓ |
| Make bookings | ✓ | ✓ (walk-in) | — | — |
| Cancel own bookings | ✓ | — | — | — |
| View own bookings/payments | ✓ | — | — | — |
| Write reviews | ✓ | — | — | — |
| Wishlist favorites | ✓ | — | — | — |
| Approve/reject bookings | — | ✓ | — | — |
| Process check-in/out | — | ✓ | — | — |
| Manage walk-in bookings | — | ✓ | — | — |
| Verify payments | — | ✓ | ✓ | ✓ |
| View occupancy | — | — | ✓ | ✓ |
| Moderate reviews | — | — | ✓ | ✓ |
| Business reports | — | — | ✓ | ✓ |
| Activity logs (business) | — | — | ✓ | ✓ |
| Full user management | — | — | — | ✓ |
| Room CRUD | — | — | — | ✓ |
| Service CRUD | — | — | — | ✓ |
| System settings | — | — | — | ✓ |
| Full audit logs | — | — | — | ✓ |
| Payroll management | — | — | — | ✓ |
| Full financial reports | — | — | — | ✓ |

---

# 3. Module-by-Module Functionality

## 3.1 Authentication & Registration

The authentication system is provided by **Laravel Breeze** and includes:

- **Registration**: New users register with name, email, phone, password. They are automatically assigned the `guest` role (role_id = 4).
- **Login**: Email + password authentication with "remember me" support.
- **Password Reset**: Forgot-password flow with email-based reset links.
- **Email Verification**: Optional email verification with signed URLs.
- **Password Confirmation**: Re-authentication required for sensitive actions (profile deletion).
- **Session Management**: Sessions stored in the `sessions` table (database driver).

### Login Security
Failed login attempts are logged via `AuditService::loginFailed()`, recording the email, IP address, user agent, and failure reason.

## 3.2 Home Page & Public Browsing

The public-facing site allows unauthenticated visitors to:

- **Home Page** (`/`): Displays featured rooms, room types, and a hero section with a search form.
- **Room Listing** (`/rooms`): Paginated grid of all active rooms with search, filter by room type/capacity/price, and a "Compare" feature.
- **Room Detail** (`/rooms/{id}`): Full room info — images, amenities, pricing, approved reviews, average rating. Shows a "Book Now" button (for authenticated users) and "Check Availability" calendar link (visible to all, redirects guests to login).
- **Room Comparison** (`/rooms/compare`): Side-by-side comparison of selected rooms.
- **Availability Calendar** (`/rooms/{id}/calendar`): A month-grid view showing room availability with color-coded dots — green (available), yellow (limited), red (booked). Prev/next month navigation, today highlight, and a legend.
- **Services** (`/services`): Lists all active add-on services with prices.
- **Contact Form** (`/contact`): Public inquiry form (name, email, phone, subject, message) stored as contact messages.

## 3.3 Booking System

### Booking Flow (Guest)
1. Guest browses rooms and clicks **"Book Now"** (requires authentication).
2. Redirected to `/bookings/create?room_id=X` where the guest selects check-in/check-out dates, number of guests, and optionally adds services.
3. On submission, `BookingService` validates dates (overlap, min/max stay), calculates pricing, and creates a booking with `status: pending`.
4. The guest is redirected to the **payment page** (`/bookings/{booking}/pay`) to complete payment.
5. After successful payment, the booking status may auto-confirm (depending on settings).

### Walk-in Booking (Receptionist)
1. Receptionist navigates to **Walk-in Booking** from their dashboard.
2. Fills guest details (name, phone, email, national ID) — if the guest already exists, the system selects them; otherwise, a new `guest`-role user is created automatically.
3. Selects room, dates, services, and any extra charges.
4. Walk-in bookings can be created as `confirmed` (with cash payment recorded) or `pending` (awaiting approval).

### Booking Statuses & State Machine

```
pending ──► confirmed ──► checked_in ──► checked_out
  │                                                  
  └──► cancelled
```

| Status | Meaning | Allowed Transitions |
|---|---|---|
| `pending` | Awaiting approval | confirmed, cancelled |
| `confirmed` | Approved and/or paid | checked_in, cancelled |
| `checked_in` | Guest has physically checked in | checked_out |
| `checked_out` | Guest has departed | terminal state |
| `cancelled` | Booking cancelled by guest or staff | terminal state |

### Cancellation Policy
The `CancellationService` reads policy settings from the database:
- **Free cancellation window**: Number of hours before check-in for full refund.
- **Cancellation fee**: Percentage charged if cancelled within the penalty window.
- **Late cancellation fee**: Higher percentage for very-late cancellations.
- Refunds are automatically calculated and applied to the booking's payments.

### Booking Pricing Calculation

```
total_nights = ceil((check_out - check_in) / (60*60*24))

subtotal     = price_per_night × total_nights
tax          = subtotal × (tax_rate / 100)
service_fee  = subtotal × 0.05
booking_fee  = 5000.00 (fixed)
total        = subtotal + tax + service_fee + booking_fee
```

Services added to a booking increase `services_total`, which is added to the `total` before payment.

### Room Availability
The `Room::scopeAvailable()` query scope checks that a room has **no overlapping bookings** in `confirmed`, `checked_in`, or `checked_out` status for the requested date range. The overlap logic ensures:
- No existing booking's date range overlaps with the new request.
- Same-day bookings are allowed up to the configured cutoff time.

## 3.4 Payment System

### Payment Flow
1. After booking is created, the guest is taken to `/bookings/{booking}/pay`.
2. Selects a **payment method**: Cash, Mobile Money, Bank Transfer, or Card/Stripe.
3. The system processes **full payment only** — no partial payments or deposits.
4. On success, the payment is recorded with `status: paid` and a unique `receipt_number` is auto-generated (format: `RCP-YYYYMMDD-XXXX`).
5. If `booking_auto_confirm` setting is enabled, the booking is automatically confirmed. Otherwise, it remains pending for receptionist approval.

### Payment Verification
- **Receptionists & Admins** can verify payments (`/payments/{payment}/verify`), marking them as officially confirmed.
- **Admins only** can unverify payments if needed.
- The verification event is logged to the audit trail.

### Refunds
- **Admins** can process refunds on paid payments via the admin payments panel.
- The refund amount is recorded, the payment status changes to `refunded`, and the audit trail logs the event.

### Receipts
- Receipts are viewable at `/payments/{payment}/receipt` and show:
  - Hotel name, receipt number, transaction ID
  - Guest name & booking reference
  - Room details, check-in/out dates
  - Breakdown: subtotal, services, discount
  - **Total matching the payment amount exactly**
- Receipts can be downloaded as PDF via DomPDF (button on receipt page).
- The PDF receipt uses the same breakdown logic.

### Payment Statuses

```
pending ──► paid ──► refunded
             │
             └──► failed (on error/decline)
```

## 3.5 Check-In / Check-Out

### Check-In Process
- Performed by a **receptionist** from the reception dashboard or booking detail page.
- Requires the booking to be in `confirmed` status.
- Creates a `CheckIn` record with timestamp, receptionist ID, guest ID, and room ID.
- Updates the booking status to `checked_in`.
- Updates the room status to `occupied`.
- Logged to audit trail via `AuditService::checkIn()`.

### Check-Out Process
- Also performed by a **receptionist**.
- Updates the booking status to `checked_out`.
- Sets `checked_out_at` on the `CheckIn` record.
- Resets room status to `available` (respecting any buffer time from settings).
- Optionally records extra charges.
- Logged to audit trail via `AuditService::checkOut()`.

### Force Check-Out (Admin)
Admins can force a check-out on any active check-in, useful when a guest has already departed but the system wasn't updated.

## 3.6 Services System

Services are add-on items guests can add to their bookings:

- **Service Types**: Laundry, Breakfast, Lunch, Dinner, Airport Transfer, Conference Hall, Spa, Room Service (configurable).
- **Adding Services**: Guests can add services during booking creation or from the booking detail page. Each service has a quantity and recorded price.
- **Pricing**: When a service is added or removed, the booking's `services_total` and `total` are recalculated.
- **Active/Inactive**: Services can be toggled active/inactive by admin.

## 3.7 Reviews & Ratings

### Guest Review Flow
1. After **check-out**, the guest can submit a review for their stay.
2. One review allowed per booking.
3. Rating is 1-5 stars, with optional comment text.
4. Reviews are created with `is_approved: false` by default.

### Moderation
- **Admins & Managers** can approve or delete reviews.
- Approved reviews are visible on the room detail page and affect the room's average rating.
- The `getAverageRatingAttribute()` accessor on the Room model calculates the average from approved reviews only.

## 3.8 Wishlist / Favorites

Authenticated guests can save rooms to a wishlist:

- **Add to Wishlist**: From the room listing (heart icon) or room detail page ("Save to Wishlist" button).
- **View Wishlist**: `/wishlist` — shows all saved rooms with a remove option.
- **Wishlist Nav Link**: Visible in the mobile menu and on the room detail page.
- **Uniqueness**: Enforced at database level with a unique constraint on `(user_id, room_id)`.
- **Guest Redirect**: Non-authenticated users who click wishlist buttons are redirected to login.

## 3.9 Notifications

The notifications system provides in-app alerts:

- **Notification Types**: Booking created, booking confirmed, payment received, check-in reminder, etc.
- **Notification Center**: `/notifications` shows all notifications for the authenticated user.
- **Mark as Read**: Individual or bulk mark-all-read functionality.
- **Unread Count**: Scope `unread()` filters unread notifications.

## 3.10 Contact Messages

The public contact form allows visitors to send inquiries:

- Stored in the `contact_messages` table with sender details and message.
- Admin can view and manage inquiries (though no dedicated admin view is built yet — the data is in the database).
- `is_read` flag tracks whether the message has been reviewed.

---

# 4. Staff Role Modules

## 4.1 Admin Module

The Admin has access to the most comprehensive management interface at `/admin`.

### Admin Dashboard (`/admin`)
Displays a KPI overview with:
- **Total Rooms** count
- **Active Bookings** (checked-in)
- **Pending Bookings** (awaiting approval)
- **Today's Check-ins / Check-outs**
- **Monthly Revenue** chart (bar graph)
- **Recent Bookings** table (last 10)

### User Management (`/admin/users`)
- **User List**: Paginated table of all users with name, email, role, status, registration date.
- **Create User**: Form to create users of any role (name, email, phone, password, role, national ID, address, gender, date of birth, country).
- **Edit User**: Update user details and role assignment.
- **Activate / Deactivate**: Toggle user active status.
- **Reset Password**: Admin can reset any user's password.
- **Role Change**: Change a user's role directly.

### Room Management (`/admin/rooms`)
- **Room List**: Paginated table with name, type, price, capacity, status, active toggle.
- **Create Room**: Form with name, type, description, price, capacity, size, images (JSON), amenities (JSON checkboxes), status.
- **Edit Room**: Update all room fields.
- **Deactivate Room**: Soft-deactivation via `is_active` flag (room hidden from public listing but data preserved).

### Booking Management (`/admin/bookings`)
- **All Bookings**: Paginated list with guest name, room, dates, total, status.
- **View Details**: Link to individual booking page.
- **Status Overview**: See booking status distribution.

### Payment Management (`/admin/payments`)
- **All Payments**: Paginated list with transaction ID, receipt number, booking reference, amount, method, status, processor.
- **Confirm Payment**: Manually confirm a pending payment.
- **Refund Payment**: Process refund with reason.

### Service Management (`/admin/services`)
- **Service List**: All services with price, category, active status.
- **Create/Edit/Deactivate Services**: Full CRUD.

### Check-In Management (`/admin/checkins`)
- **All Check-ins**: Paginated list with guest, room, check-in time, check-out time, receptionist.
- **Force Check-out**: End any active stay.

### Review Moderation (`/admin/reviews`)
- **All Reviews**: Paginated list with rating, comment, room, guest, approval status.
- **Approve Review**: Make review visible to public.

### System Settings (`/admin/settings`)
Key-value configuration management for:
- **Hotel Info**: Hotel name, address, phone, email, currency.
- **Booking Rules**: Tax rate, check-in/out times, min/max stay, advance booking window, auto-confirm toggle.
- **Payment Settings**: Enable/disable online payments, payment methods.
- **Cancellation Policy**: Free cancellation window, cancellation fee percentage, late cancellation fee.

### Reports Module (`/admin/reports`)
Comprehensive reporting system (see Section 6).

### Payroll Module (`/admin/payroll`)
Complete payroll management (see Section 5).

### Audit Logs (`/admin/audit-logs`)
Full system audit trail (see Section 7).

## 4.2 Manager Module

Managers access their dashboard at `/manager`.

### Manager Dashboard (`/manager`)
- **Total Rooms**
- **Active Bookings**
- **Available Rooms**
- **Monthly Revenue** chart
- **Recent Bookings** table

### Occupancy (`/manager/occupancy`)
- **Room Status Overview**: Table showing all rooms with their current status (available, occupied, reserved, maintenance).
- **Quick View**: See which rooms are free and which have guests.

### Review Moderation (`/manager/reviews`)
- Same review approval functionality as admin, but limited to review management only.

### Reports (`/manager/reports`)
Business-scoped reports (see Section 6).

### Activity Log (`/manager/audit-logs`)
Business-scoped audit log (see Section 7).

## 4.3 Receptionist Module

Receptionists access their front-desk interface at `/receptionist`.

### Reception Dashboard (`/receptionist`)
- **Today's check-ins** count
- **Today's check-outs** count
- **Active stays** count
- **Available rooms** count
- **Upcoming checkouts** table
- **Today's bookings** table
- **Available rooms** table

### Booking Search (`/receptionist/search`)
Search bookings by:
- Booking number
- Guest name
- Guest email
Results show matching bookings with actions (approve, reject, check-in, check-out).

### Walk-in Booking (`/receptionist/walk-in`)
Creates a booking on the spot for walk-in guests:
1. **Guest Information**: Searches existing guests by name/phone/email. If no match, creates a new user with `guest` role automatically. All such individuals are recorded as **guests** in the system — walk-in guests are identical to registered guests in the data model, just onboarded by staff rather than self-registering.
2. **Room Selection**: Choose from available rooms.
3. **Dates**: Check-in/out dates.
4. **Services**: Optional add-on services.
5. **Extra Charges**: Optional additional charges.
6. The booking is created as `confirmed` if payment is handled on the spot.

### Booking Actions
- **Approve Booking**: Confirms a pending booking.
- **Reject Booking**: Rejects with a reason.
- **Process Check-In**: Physical check-in (updates booking + room status).
- **Process Check-Out**: Physical check-out (updates booking + room status, records checkout time).

---

# 5. Payroll Management

The payroll system handles employee salary administration with a complete workflow from generation to payment.

## 5.1 Data Model

### `payrolls` Table
| Field | Type | Description |
|---|---|---|
| `user_id` | FK→users | Employee receiving the salary |
| `month` | string (7) | Period in YYYY-MM format |
| `base_salary` | decimal(10,2) | Base monthly salary |
| `bonus` | decimal(10,2) | Additional bonus amount |
| `deductions` | decimal(10,2) | Deductions (tax, penalties, etc.) |
| `total_salary` | decimal(10,2) | Computed: base + bonus - deductions |
| `status` | enum | pending → approved → paid |
| `processed_by` | FK→users | Admin who processed the record |
| `paid_at` | timestamp | When payment was made |
| `notes` | text | Optional remarks |

Unique constraint on `(user_id, month)` — one payroll record per employee per month.

### `employee_bank_details` Table
Stores employee payment method information:
- **Payment Types**: Bank Transfer, Mobile Money (Vodacom, Airtel, Tigo, Halotel), Card.
- **Bank Fields**: Bank name, account number, account holder name.
- **Mobile Money Fields**: Provider, mobile number.
- **Card Fields**: Last 4 digits, card holder name.

## 5.2 Payroll Workflow

```
generateAll() ──► pending ──► approve() ──► approved ──► markPaid() ──► paid
                                                                    │
                                                              downloadPayslip()
```

### Auto-Generation (`generateAll()`)
Scans all staff users (admin, manager, receptionist roles) and creates payroll records for the current month if none exist. Base salaries are assigned by role:
- **Admin**: 1,500,000 TZS
- **Manager**: 800,000 TZS
- **Receptionist**: 400,000 TZS
- **Other**: 300,000 TZS

### Manual Creation
Admin can create individual payroll records on the `/admin/payroll/create` page with:
- Employee selection (shows only employees without existing payroll for selected month).
- Month selector (defaults to current month).
- Base salary (auto-populated by role, editable).
- Bonus and deductions.
- Optional notes.

### Approval Flow
1. **Pending** → Admin reviews and clicks "Approve" — status changes to `approved`, `processed_by` is set.
2. **Approved** → Admin clicks "Mark Paid" — status changes to `paid`, `paid_at` timestamp recorded.
3. **Paid** → Terminal state (cannot be deleted or modified).

### Payslip PDF
Each payroll record has a downloadable payslip PDF containing:
- Hotel header (BungeStay)
- Employee details (name, email, role)
- Salary breakdown (base, bonus, deductions, total)
- Payment status and dates
- Processor information
- Notes

### Employee Bank Details
Staff can register their payment method at `/profile/bank-details`:
- Mobile Money: Provider + phone number.
- Bank Transfer: Bank name + account number + holder name.
- Card: Last 4 digits + holder name.
- This information appears on the payroll detail page for quick reference during payment processing.

---

# 6. Reporting System

## 6.1 ReportService Architecture

The `ReportService` class in `app/Services/ReportService.php` contains all query logic, shared by both `AdminController` and `ManagerController`. This keeps the reporting logic centralized and avoids duplication.

### Service Methods

#### `financialSummary($startDate, $endDate)`
- Total revenue (sum of all paid payments)
- Total payment count
- Average payment amount
- Revenue breakdown by payment method (cash, mobile_money, stripe, bank_transfer)
- Daily revenue trend (for charting)

#### `refundSummary($startDate, $endDate)`
- Total amount refunded
- Total refund count

#### `pendingPayments()`
- All payments with `pending` status
- Includes booking and guest details
- Useful for identifying unconfirmed transactions

#### `failedPayments($startDate, $endDate)`
- Count of failed payments
- List of failed payments with details

#### `bookingSummary($startDate, $endDate)`
- Total bookings
- Bookings by status (pending, confirmed, checked_in, checked_out, cancelled)
- Monthly booking trend
- Booking conversion rate (percentage of checked_out vs total non-cancelled)
- Daily booking trend

#### `roomPerformance($startDate, $endDate)`
- Total room stats (count, capacity)
- Occupancy rate (occupied nights / total available nights)
- Most booked room
- Least booked room
- Top revenue-generating rooms
- Per-room performance data

#### `userSummary($startDate, $endDate)`
- Total user count
- Active user count
- Guest user count
- New user registration trend

#### `revenueByRoomType($startDate, $endDate)`
- Revenue grouped by room type
- For pie chart visualization

#### `reviewsSummary($startDate, $endDate)`
- Total review count
- Average rating
- Pending review count
- Per-room review stats
- Rating distribution (1-5 star counts)

## 6.2 Admin Reports (`/admin/reports`)

Full financial and operational reporting dashboard:

### Date Filter
Start date and end date pickers that filter all data on the page.

### KPI Cards
- Total Revenue, Total Bookings, Occupancy Rate, Average Rating — displayed in a 4-card grid with color-coded borders.

### Charts (Chart.js)
Three charts rendered on the page:
1. **Revenue Trend** — Line chart: daily revenue over the selected period.
2. **Booking Status Distribution** — Bar chart: bookings by status.
3. **Revenue by Room Type** — Pie/doughnut chart: revenue share across room categories.

### Tables
- **Failed Payments**: List of failed transactions with details.
- **Pending Payments**: List of unverified payments requiring action.

### Export Options
- **PDF Export**: Selectable report type (financial, bookings, rooms, revenue-by-type) → rendered via dedicated Blade template → downloaded as PDF.
- **CSV Export**: Same types → streamed as comma-separated values file.

PDF templates are stored in `resources/views/reports/`:
- `admin-financial.blade.php`, `admin-bookings.blade.php`, `admin-rooms.blade.php`, `admin-revenue-by-type.blade.php`
- `manager-financial.blade.php`, `manager-bookings.blade.php`, `manager-rooms.blade.php`

## 6.3 Manager Reports (`/manager/reports`)

Business-scoped reporting dashboard with the same KPI cards, charts, date filter, and export functionality as admin. Managers see operational data without sensitive financial or configuration information.

---

# 7. Audit Trail System

## 7.1 Data Model

The `audit_logs` table uses a polymorphic relationship (`auditable_id` + `auditable_type`) to associate logs with any model in the system.

| Field | Type | Description |
|---|---|---|
| `user_id` | FK→users (nullable) | Who performed the action (null for system events) |
| `action` | string | Action code (e.g., BOOKING_CREATED, PAYMENT_PROCESSED) |
| `module` | string | System module (Booking, Payment, CheckIn, User, Payroll, Security, Report, Review) |
| `description` | text | Human-readable description of the event |
| `ip_address` | string(45) | Client IP address |
| `user_agent` | string | Browser/user-agent string |
| `auditable_id` | integer (nullable) | ID of the related model |
| `auditable_type` | string (nullable) | Class name of the related model |
| `old_values` | JSON (nullable) | Previous state of changed data |
| `new_values` | JSON (nullable) | New state of changed data |

## 7.2 AuditService Methods

The `AuditService` provides named methods for each auditable action:

| Method | Module | Action Code | When Triggered |
|---|---|---|---|
| `bookingCreated($booking)` | Booking | BOOKING_CREATED | New booking created |
| `bookingStatusChanged($booking, $old, $new)` | Booking | BOOKING_STATUS_CHANGED | Status transition |
| `paymentProcessed($payment)` | Payment | PAYMENT_PROCESSED | Payment completed |
| `paymentVerified($payment)` | Payment | PAYMENT_VERIFIED | Payment verified |
| `checkIn($booking)` | CheckIn | CHECKIN | Guest checked in |
| `checkOut($booking)` | CheckIn | CHECKOUT | Guest checked out |
| `reviewSubmitted($review)` | Review | REVIEW_SUBMITTED | Review posted |
| `userCreated($user)` | User | USER_CREATED | New user registered/created |
| `userUpdated($user, $changes)` | User | USER_UPDATED | User profile updated |
| `payrollGenerated($payroll)` | Payroll | PAYROLL_GENERATED | Payroll record created |
| `payrollApproved($payroll)` | Payroll | PAYROLL_APPROVED | Payroll approved |
| `payrollPaid($payroll)` | Payroll | PAYROLL_PAID | Payroll marked paid |
| `loginFailed($email, $reason)` | Security | LOGIN_FAILED | Failed login attempt |
| `reportGenerated($type, $format)` | Report | REPORT_GENERATED | Report exported |

A generic `log()` method is also available for custom audit entries.

## 7.3 Viewing Audit Logs

### Admin View (`/admin/audit-logs`)
- Complete visibility into all system actions.
- Filters: Module, Action, Search (description), Date range (from/to).
- Paginated results with columns: Time, User, Module, Action, Description, IP Address.

### Manager View (`/manager/audit-logs`)
- Scoped to business-relevant modules only: Booking, Payment, CheckIn, Review.
- Filters: Module, Date range.
- Same paginated table format without IP address for privacy.

---

# 8. Navigation & UI Structure

## 8.1 Global Navigation

The sticky top navigation bar (`layouts/navigation.blade.php`) uses Alpine.js for:
- **Scroll effect**: Transparent-to-solid background transition on scroll.
- **Mobile menu**: Slide-down menu with smooth transitions.
- **Profile avatar**: Circle with first-name initial + full name.

### Public Navigation
- Home, Rooms, Services, Contact — visible to all visitors.
- Login / Register buttons for guests.

### Authenticated Navigation
- Role-specific links appear based on user role:
  - **Admin**: Admin, Payroll, Audit Logs links.
  - **Manager**: Manager, Activity Log links.
  - **Receptionist**: Reception link.
- Profile icon with initial + full name.
- Logout button.

### Mobile Menu
Same links organized vertically with hover effects.

## 8.2 Back Button
The `app.blade.php` layout includes a global back button (hidden on home page). It uses `url()->previous()` or an explicit `back-url` attribute passed from individual views.

## 8.3 Layout Structure
- `app.blade.php`: Main authenticated layout with navigation, sidebar, and content area.
- `guest.blade.php`: Minimal layout for login/register pages.

---

# 9. Component Library

The system includes reusable Blade components in `resources/views/components/`:

| Component | Purpose |
|---|---|
| `application-logo` | BS logo with gradient |
| `auth-session-status` | Session status messages |
| `danger-button` | Red action button |
| `dropdown` | Dropdown menu with Alpine.js |
| `dropdown-link` | Dropdown menu item |
| `input-error` | Validation error message |
| `input-label` | Form label |
| `modal` | Confirmation dialog |
| `nav-link` | Navigation link with active state |
| `primary-button` | Primary action button |
| `responsive-nav-link` | Mobile navigation link |
| `secondary-button` | Secondary action button |
| `text-input` | Styled text input |

---

# 10. Services Layer

## 10.1 BookingService
- `validateDates()`: Validates check-in/out against rules and existing bookings.
- `hasOverlap()`: Checks room availability for date range.
- `calculateNights()`: Computes number of nights.
- `calculateTotal()`: Full pricing breakdown with tax and fees.
- `markRoomAfterCheckOut()`: Resets room status post-checkout.

## 10.2 CancellationService
- `getPolicy()`: Reads cancellation policy from settings.
- `getPolicySummary()`: Returns human-readable policy.
- `canCancel()`: Determines if cancellation is allowed and computes penalty/refund.
- `cancel()`: Executes cancellation with transaction safety.

## 10.3 ReportService
As detailed in Section 6.1.

## 10.4 AuditService
As detailed in Section 7.2.

---

# 11. System Configuration

## 11.1 Database-Driven Settings

Stored in the `settings` table as key-value pairs. Managed via the admin settings page.

| Setting Key | Default Value | Description |
|---|---|---|
| `hotel_name` | BungeStay | Hotel display name |
| `hotel_address` | — | Physical address |
| `hotel_phone` | — | Contact number |
| `hotel_email` | — | Contact email |
| `currency` | TZS | Currency code for all pricing |
| `tax_rate` | 18 | Tax percentage on bookings |
| `checkin_time` | 14:00 | Default check-in time |
| `checkout_time` | 11:00 | Default check-out time |
| `min_stay_days` | 1 | Minimum nights per booking |
| `max_stay_days` | 30 | Maximum nights per booking |
| `advance_booking_days` | 90 | Max days ahead for booking |
| `booking_auto_confirm` | 0 | Auto-confirm after payment |
| `enable_online_payments` | 1 | Toggle online payment methods |
| `payment_methods` | — | Available payment methods |
| `cancellation_free_hours` | 48 | Hours before check-in for free cancel |
| `cancellation_fee_percent` | 50 | Penalty within penalty window |
| `late_cancellation_fee_percent` | 100 | Penalty for very late cancels |

## 11.2 Environment Configuration

The `.env` file controls:
- Database connection (default: SQLite, configurable to MySQL/PostgreSQL)
- Session driver: `database`
- Cache store: `database`
- Queue connection: `database`
- Mail driver: `log` (configurable to SMTP)
- Application URL and environment

---

# 12. Frontend Build Pipeline

## 12.1 Vite Configuration
- Entry points: `resources/js/app.js` (main), `resources/js/chart.js` (Chart.js global registration).
- CSS: `resources/css/app.css` processed by PostCSS + Tailwind CSS.
- Plugins: `laravel-vite-plugin`, `@tailwindcss/vite`.

## 12.2 Chart.js Integration
- Chart.js 4.5.1 installed as an npm dependency.
- Registered globally via `window.Chart = require('chart.js')` in `resources/js/chart.js`.
- Charts are initialized in Blade templates using inline `<script>` blocks that build Chart.js configurations.

---

# 13. Data Flow Examples

## 13.1 Complete Booking Lifecycle

```
1. Guest browses rooms on /rooms
2. Guest clicks "Book Now" on a room → /bookings/create?room_id=X
3. Guest selects dates, guests, optional services → submits form
4. BookingController@store:
   a. BookingService validates dates (overlap check)
   b. BookingService calculates pricing
   c. Booking created with status 'pending'
   d. AuditService logs BOOKING_CREATED
   e. Notification created for guest
5. Redirected to /bookings/{booking}/pay
6. Guest selects payment method and submits payment
7. PaymentController@store:
   a. Payment created with receipt_number
   b. If auto_confirm enabled → booking status = confirmed
   c. AuditService logs PAYMENT_PROCESSED
8. Receptionist dashboard shows new booking
9. At check-in day:
   a. Receptionist processes check-in
   b. Booking → checked_in, Room → occupied
   c. CheckIn record created
   d. AuditService logs CHECKIN
10. At check-out day:
    a. Receptionist processes check-out
    b. Booking → checked_out, Room → available
    c. CheckIn.checked_out_at set
    d. AuditService logs CHECKOUT
11. Guest can now submit a review
12. Admin/Manager approves review
13. Review visible on room page
```

## 13.2 Walk-in Booking Flow

```
1. Guest arrives at front desk
2. Receptionist opens /receptionist/walk-in
3. Searches for guest by name/phone/email
   a. If found → selects existing guest
   b. If not found → fills guest details → new User created (role: guest)
4. Selects room from available list
5. Sets check-in/check-out dates
6. Optionally selects services and extra charges
7. Submits → Booking created as 'confirmed' (or 'pending')
8. If cash payment collected → Payment recorded as 'paid'
```

## 13.3 Payroll Cycle

```
1. Admin navigates to /admin/payroll
2. Clicks "Auto-Generate" → creates payroll for all employees
3. Views pending payroll records
4. Reviews each record → clicks "Approve"
5. Status changes to 'approved' (audit logged)
6. Processes payment externally → clicks "Mark Paid"
7. Status changes to 'paid', timestamp recorded (audit logged)
8. Employee can download payslip PDF anytime
```

---

# 14. Security Considerations

| Concern | Implementation |
|---|---|
| **Authentication** | Laravel Breeze with database sessions |
| **Authorization** | RoleMiddleware checks slug-based role access |
| **Password Security** | Bcrypt hashing (default Laravel) |
| **CSRF Protection** | All POST/PUT/DELETE routes use CSRF tokens |
| **XSS Prevention** | Blade's `{{ }}` auto-escapes output |
| **SQL Injection** | Eloquent ORM with parameterized queries |
| **Input Validation** | Form requests and inline validation rules |
| **Session Security** | Database driver, HTTP-only cookies |
| **Email Verification** | Signed URLs for verification links |
| **Audit Trail** | All sensitive actions logged with IP and user agent |
| **Payment Data** | No raw card numbers stored; only last 4 digits |
| **Account Deactivation** | Admin can disable users without deleting data |

---

# 15. File Structure Summary

```
bungestay/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # 19 controllers (web + auth)
│   │   ├── Middleware/          # RoleMiddleware
│   │   └── Requests/            # Auth/LoginRequest, ProfileUpdateRequest
│   ├── Models/                  # 16 Eloquent models
│   ├── Providers/               # AppServiceProvider
│   ├── Services/                # 4 service classes
│   └── View/Components/         # AppLayout, GuestLayout
├── bootstrap/                   # Laravel bootstrap
├── config/                      # 12 config files
├── database/
│   ├── migrations/              # 19 migration files (20 tables)
│   └── seeders/                 # DatabaseSeeder with initial data
├── public/                      # Front controller
├── resources/
│   └── views/                   # 83 Blade template files
│       ├── admin/               # 13 admin views + payroll/
│       ├── manager/             # 5 manager views
│       ├── receptionist/        # 3 receptionist views
│       ├── auth/                # 6 auth views
│       ├── bookings/            # 3 booking views
│       ├── payments/            # 3 payment views
│       ├── profile/             # 4 profile views (incl. partials)
│       ├── reports/             # 7 PDF export templates
│       ├── rooms/               # 5 room views
│       ├── components/          # 13 Blade components
│       ├── layouts/             # 3 layout views
│       └── ...                  # services, notifications, wishlist, etc.
├── routes/
│   ├── web.php                  # 80+ routes
│   ├── auth.php                 # Auth routes
│   └── console.php              # Artisan commands
├── storage/                     # Logs, cache, framework files
├── tests/                       # Feature & Unit tests
├── composer.json
├── package.json
├── vite.config.js
└── tailwind.config.js
```

---

# 16. Future Enhancements

The following areas are identified for future development:

1. **Audit Log Integration**: Connect existing controllers (Booking, Payment, CheckIn, Review, User) to `AuditService` methods so all actions are logged automatically.
2. **Dashboard Widgets**: Add payroll summary cards and audit log activity feed to admin/manager dashboards.
3. **Email Notifications**: Configure SMTP mail driver and implement transactional emails (booking confirmation, payment receipt, check-in reminder).
4. **Online Payment Gateway**: Integrate with a real payment gateway (e.g., Stripe, Selcom, NMB) for live transaction processing.
5. **Multi-Property Support**: Allow the system to manage multiple hotel/lodge properties from a single installation.
6. **Housekeeping Module**: Add room cleaning status tracking and assignment.
7. **Inventory Management**: Track supplies linked to room services.
8. **Advanced Reporting**: Add custom report builder and scheduled PDF email reports.
9. **API Layer**: Build REST API for integration with external systems (OTA channels, POS systems).

---

*Report generated from the BungeStay codebase — SMART-LODGE-SYSTEM*

*June 2026*
