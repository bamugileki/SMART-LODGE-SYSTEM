<?php

namespace Database\Seeders;

use App\Models\QuickLink;
use Illuminate\Database\Seeder;

class QuickLinkSeeder extends Seeder
{
    public function run(): void
    {
        $links = [
            // ─── FOOTER: Quick Links ───
            ['label' => 'Home', 'url' => '/', 'section' => 'footer', 'group' => 'Quick Links', 'order' => 1],
            ['label' => 'Rooms', 'url' => '/rooms', 'section' => 'footer', 'group' => 'Quick Links', 'order' => 2],
            ['label' => 'Book Now', 'url' => '/bookings/create', 'section' => 'footer', 'group' => 'Quick Links', 'order' => 3, 'roles' => 'guest,receptionist,manager,admin'],
            ['label' => 'Availability', 'url' => '/rooms/search', 'section' => 'footer', 'group' => 'Quick Links', 'order' => 4],
            ['label' => 'Gallery', 'url' => '/rooms', 'section' => 'footer', 'group' => 'Quick Links', 'order' => 5],
            ['label' => 'Contact', 'url' => '/contact', 'section' => 'footer', 'group' => 'Quick Links', 'order' => 6],

            // ─── FOOTER: Guest Services ───
            ['label' => 'My Bookings', 'url' => '/bookings', 'section' => 'footer', 'group' => 'Guest Services', 'order' => 1, 'roles' => 'guest,receptionist,manager,admin'],
            ['label' => 'Payment Status', 'url' => '/payments', 'section' => 'footer', 'group' => 'Guest Services', 'order' => 2, 'roles' => 'guest,receptionist,manager,admin'],
            ['label' => 'Cancellation Policy', 'url' => '/contact', 'section' => 'footer', 'group' => 'Guest Services', 'order' => 3],
            ['label' => 'FAQ', 'url' => '/contact', 'section' => 'footer', 'group' => 'Guest Services', 'order' => 4],

            // ─── FOOTER: Company ───
            ['label' => 'About Us', 'url' => '/', 'section' => 'footer', 'group' => 'Company', 'order' => 1],
            ['label' => 'Privacy Policy', 'url' => '/contact', 'section' => 'footer', 'group' => 'Company', 'order' => 2],
            ['label' => 'Terms & Conditions', 'url' => '/contact', 'section' => 'footer', 'group' => 'Company', 'order' => 3],

            // ─── FOOTER: Support ───
            ['label' => 'WhatsApp Support', 'url' => 'https://wa.me/255689045666', 'section' => 'footer', 'group' => 'Support', 'order' => 1],
            ['label' => 'Email Support', 'url' => 'mailto:info@bungestay.com', 'section' => 'footer', 'group' => 'Support', 'order' => 2],
            ['label' => 'Help Center', 'url' => '/contact', 'section' => 'footer', 'group' => 'Support', 'order' => 3],

            // ─── GUEST DASHBOARD ───
            ['label' => 'Browse Rooms', 'url' => '/rooms', 'icon' => 'home', 'section' => 'guest_dashboard', 'order' => 1],
            ['label' => 'Book Room', 'url' => '/bookings/create', 'icon' => 'calendar', 'section' => 'guest_dashboard', 'order' => 2],
            ['label' => 'My Bookings', 'url' => '/bookings', 'icon' => 'clipboard-list', 'section' => 'guest_dashboard', 'order' => 3],
            ['label' => 'Make Payment', 'url' => '/payments', 'icon' => 'credit-card', 'section' => 'guest_dashboard', 'order' => 4],
            ['label' => 'Download Receipt', 'url' => '/payments', 'icon' => 'download', 'section' => 'guest_dashboard', 'order' => 5],
            ['label' => 'Leave Review', 'url' => '/bookings', 'icon' => 'star', 'section' => 'guest_dashboard', 'order' => 6],
            ['label' => 'Update Profile', 'url' => '/profile', 'icon' => 'user', 'section' => 'guest_dashboard', 'order' => 7],
            ['label' => 'Notifications', 'url' => '/notifications', 'icon' => 'bell', 'section' => 'guest_dashboard', 'order' => 8],

            // ─── RECEPTIONIST DASHBOARD ───
            ['label' => 'New Booking', 'url' => '/receptionist/walk-in', 'icon' => 'plus-circle', 'section' => 'receptionist_dashboard', 'order' => 1],
            ['label' => 'Room Availability', 'url' => '/receptionist', 'icon' => 'grid', 'section' => 'receptionist_dashboard', 'order' => 2],
            ['label' => 'Check-In Guest', 'url' => '/receptionist', 'icon' => 'log-in', 'section' => 'receptionist_dashboard', 'order' => 3],
            ['label' => 'Check-Out Guest', 'url' => '/receptionist', 'icon' => 'log-out', 'section' => 'receptionist_dashboard', 'order' => 4],
            ['label' => 'Verify Payment', 'url' => '/admin/payments', 'icon' => 'check-circle', 'section' => 'receptionist_dashboard', 'order' => 5],
            ['label' => 'View Receipts', 'url' => '/admin/payments', 'icon' => 'file-text', 'section' => 'receptionist_dashboard', 'order' => 6],
            ['label' => 'Guest List', 'url' => '/receptionist', 'icon' => 'users', 'section' => 'receptionist_dashboard', 'order' => 7],
            ['label' => "Today's Arrivals", 'url' => '/receptionist', 'icon' => 'calendar', 'section' => 'receptionist_dashboard', 'order' => 8],
            ['label' => "Today's Departures", 'url' => '/receptionist', 'icon' => 'calendar-check', 'section' => 'receptionist_dashboard', 'order' => 9],

            // ─── MANAGER DASHBOARD ───
            ['label' => 'Revenue Reports', 'url' => '/manager/reports', 'icon' => 'trending-up', 'section' => 'manager_dashboard', 'order' => 1],
            ['label' => 'Occupancy Reports', 'url' => '/manager/occupancy', 'icon' => 'bar-chart', 'section' => 'manager_dashboard', 'order' => 2],
            ['label' => 'Booking Reports', 'url' => '/manager/reports', 'icon' => 'book-open', 'section' => 'manager_dashboard', 'order' => 3],
            ['label' => 'Customer Reviews', 'url' => '/manager/reviews', 'icon' => 'star', 'section' => 'manager_dashboard', 'order' => 4],
            ['label' => 'Payroll Summary', 'url' => '/admin/payroll', 'icon' => 'dollar-sign', 'section' => 'manager_dashboard', 'order' => 5],
            ['label' => 'Activity Logs', 'url' => '/manager/audit-logs', 'icon' => 'activity', 'section' => 'manager_dashboard', 'order' => 6],
            ['label' => 'Business Analytics', 'url' => '/manager/reports', 'icon' => 'pie-chart', 'section' => 'manager_dashboard', 'order' => 7],

            // ─── ADMIN DASHBOARD QUICK LINKS ───
            // System Management
            ['label' => 'Dashboard', 'url' => '/admin', 'icon' => 'layout-dashboard', 'section' => 'admin_dashboard', 'group' => 'System Management', 'order' => 1],
            ['label' => 'Manage Users', 'url' => '/admin/users', 'icon' => 'users', 'section' => 'admin_dashboard', 'group' => 'System Management', 'order' => 2],
            ['label' => 'Manage Employees', 'url' => '/admin/users', 'icon' => 'briefcase', 'section' => 'admin_dashboard', 'group' => 'System Management', 'order' => 3],
            ['label' => 'Roles & Permissions', 'url' => '/admin/settings', 'icon' => 'shield', 'section' => 'admin_dashboard', 'group' => 'System Management', 'order' => 4],
            ['label' => 'Audit Logs', 'url' => '/admin/audit-logs', 'icon' => 'clipboard-list', 'section' => 'admin_dashboard', 'group' => 'System Management', 'order' => 5],

            // Hotel Management
            ['label' => 'Manage Rooms', 'url' => '/admin/rooms', 'icon' => 'bed', 'section' => 'admin_dashboard', 'group' => 'Hotel Management', 'order' => 6],
            ['label' => 'Room Categories', 'url' => '/admin/rooms', 'icon' => 'layers', 'section' => 'admin_dashboard', 'group' => 'Hotel Management', 'order' => 7],
            ['label' => 'Amenities', 'url' => '/admin/services', 'icon' => 'wifi', 'section' => 'admin_dashboard', 'group' => 'Hotel Management', 'order' => 8],
            ['label' => 'Availability Calendar', 'url' => '/admin/checkins', 'icon' => 'calendar', 'section' => 'admin_dashboard', 'group' => 'Hotel Management', 'order' => 9],

            // Financial Management
            ['label' => 'Payments', 'url' => '/admin/payments', 'icon' => 'credit-card', 'section' => 'admin_dashboard', 'group' => 'Financial Management', 'order' => 10],
            ['label' => 'Receipts', 'url' => '/admin/payments', 'icon' => 'receipt', 'section' => 'admin_dashboard', 'group' => 'Financial Management', 'order' => 11],
            ['label' => 'Payroll', 'url' => '/admin/payroll', 'icon' => 'dollar-sign', 'section' => 'admin_dashboard', 'group' => 'Financial Management', 'order' => 12],
            ['label' => 'Financial Reports', 'url' => '/admin/reports', 'icon' => 'trending-up', 'section' => 'admin_dashboard', 'group' => 'Financial Management', 'order' => 13],

            // Booking Management
            ['label' => 'All Bookings', 'url' => '/admin/bookings', 'icon' => 'book-open', 'section' => 'admin_dashboard', 'group' => 'Booking Management', 'order' => 14],
            ['label' => 'Pending Bookings', 'url' => '/admin/bookings?status=pending', 'icon' => 'clock', 'section' => 'admin_dashboard', 'group' => 'Booking Management', 'order' => 15],
            ['label' => 'Cancelled Bookings', 'url' => '/admin/bookings?status=cancelled', 'icon' => 'x-circle', 'section' => 'admin_dashboard', 'group' => 'Booking Management', 'order' => 16],
            ['label' => 'Booking Calendar', 'url' => '/admin/checkins', 'icon' => 'calendar', 'section' => 'admin_dashboard', 'group' => 'Booking Management', 'order' => 17],

            // Reports & Analytics
            ['label' => 'Revenue Reports', 'url' => '/admin/reports', 'icon' => 'trending-up', 'section' => 'admin_dashboard', 'group' => 'Reports & Analytics', 'order' => 18],
            ['label' => 'Occupancy Reports', 'url' => '/admin/reports', 'icon' => 'bar-chart', 'section' => 'admin_dashboard', 'group' => 'Reports & Analytics', 'order' => 19],
            ['label' => 'User Reports', 'url' => '/admin/reports', 'icon' => 'users', 'section' => 'admin_dashboard', 'group' => 'Reports & Analytics', 'order' => 20],
            ['label' => 'Security Reports', 'url' => '/admin/audit-logs', 'icon' => 'shield', 'section' => 'admin_dashboard', 'group' => 'Reports & Analytics', 'order' => 21],
            ['label' => 'Export Reports', 'url' => '/admin/reports', 'icon' => 'download', 'section' => 'admin_dashboard', 'group' => 'Reports & Analytics', 'order' => 22],

            // System Settings
            ['label' => 'Hotel Settings', 'url' => '/admin/settings', 'icon' => 'settings', 'section' => 'admin_dashboard', 'group' => 'System Settings', 'order' => 23],
            ['label' => 'Payment Settings', 'url' => '/admin/settings', 'icon' => 'credit-card', 'section' => 'admin_dashboard', 'group' => 'System Settings', 'order' => 24],
            ['label' => 'Notification Settings', 'url' => '/admin/settings', 'icon' => 'bell', 'section' => 'admin_dashboard', 'group' => 'System Settings', 'order' => 25],
            ['label' => 'Security Settings', 'url' => '/admin/settings', 'icon' => 'lock', 'section' => 'admin_dashboard', 'group' => 'System Settings', 'order' => 26],
            ['label' => 'Backup & Restore', 'url' => '/admin/settings', 'icon' => 'hard-drive', 'section' => 'admin_dashboard', 'group' => 'System Settings', 'order' => 27],

            // ─── ADMIN DASHBOARD CARDS ───
            ['label' => 'Total Bookings', 'url' => '/admin/bookings', 'icon' => 'calendar', 'section' => 'admin_cards', 'order' => 1],
            ['label' => 'Available Rooms', 'url' => '/admin/rooms', 'icon' => 'home', 'section' => 'admin_cards', 'order' => 2],
            ['label' => 'Occupied Rooms', 'url' => '/admin/checkins', 'icon' => 'users', 'section' => 'admin_cards', 'order' => 3],
            ['label' => "Today's Revenue", 'url' => '/admin/reports', 'icon' => 'dollar-sign', 'section' => 'admin_cards', 'order' => 4],
            ['label' => 'Total Guests', 'url' => '/admin/users', 'icon' => 'user-plus', 'section' => 'admin_cards', 'order' => 5],
            ['label' => 'Pending Check-Ins', 'url' => '/admin/bookings?status=confirmed', 'icon' => 'log-in', 'section' => 'admin_cards', 'order' => 6],
            ['label' => 'Pending Check-Outs', 'url' => '/admin/checkins', 'icon' => 'log-out', 'section' => 'admin_cards', 'order' => 7],
            ['label' => 'System Alerts', 'url' => '/admin/audit-logs', 'icon' => 'alert-triangle', 'section' => 'admin_cards', 'order' => 8],
        ];

        foreach ($links as $link) {
            QuickLink::create($link);
        }
    }
}
