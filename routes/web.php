<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuickLinkController;
use App\Http\Controllers\ReceptionistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/search', [HomeController::class, 'search'])->name('rooms.search');
Route::get('/rooms/compare', [RoomController::class, 'compare'])->name('rooms.compare');
Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show');
Route::get('/rooms/{id}/calendar', [RoomController::class, 'calendar'])->name('rooms.calendar');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    Route::get('/bookings/{booking}/pay', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/bookings/{booking}/pay', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');
    Route::get('/payments/{payment}/download-receipt', [PaymentController::class, 'downloadReceipt'])->name('payments.download-receipt');
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');

    Route::middleware('role:receptionist,admin')->group(function () {
        Route::post('/payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
    });

    Route::middleware('role:admin')->group(function () {
        Route::post('/payments/{payment}/unverify', [PaymentController::class, 'unverify'])->name('payments.unverify');
    });

    Route::get('/bookings/{booking}/review', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/bookings/{booking}/review', [ReviewController::class, 'store'])->name('reviews.store');

    Route::post('/bookings/{booking}/services', [ServiceController::class, 'addToBooking'])->name('bookings.services.add');
    Route::delete('/bookings/{booking}/services/{service}', [ServiceController::class, 'removeFromBooking'])->name('bookings.services.remove');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::post('/wishlist/{room}/remove', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/admin/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::post('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::post('/admin/users/{user}/destroy', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
        Route::post('/admin/users/{user}/activate', [AdminController::class, 'activateUser'])->name('admin.users.activate');
        Route::post('/admin/users/{user}/reset-password', [AdminController::class, 'resetUserPassword'])->name('admin.users.reset-password');
        Route::post('/admin/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.update-role');
        Route::get('/admin/rooms', [AdminController::class, 'rooms'])->name('admin.rooms');
        Route::post('/admin/rooms', [AdminController::class, 'storeRoom'])->name('admin.rooms.store');
        Route::match(['put', 'post'], '/admin/rooms/{room}', [AdminController::class, 'updateRoom'])->name('admin.rooms.update');
        Route::post('/admin/rooms/{room}/destroy', [AdminController::class, 'destroyRoom'])->name('admin.rooms.destroy');
        Route::get('/admin/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
        Route::get('/admin/services', [AdminController::class, 'services'])->name('admin.services');
        Route::post('/admin/services', [AdminController::class, 'storeService'])->name('admin.services.store');
        Route::post('/admin/services/{service}', [AdminController::class, 'updateService'])->name('admin.services.update');
        Route::post('/admin/services/{service}/destroy', [AdminController::class, 'destroyService'])->name('admin.services.destroy');
        Route::get('/admin/payments', [AdminController::class, 'payments'])->name('admin.payments');
        Route::post('/admin/payments/{payment}/confirm', [AdminController::class, 'confirmPayment'])->name('admin.payments.confirm');
        Route::post('/admin/payments/{payment}/refund', [AdminController::class, 'refundPayment'])->name('admin.payments.refund');
        Route::get('/admin/checkins', [AdminController::class, 'checkins'])->name('admin.checkins');
        Route::post('/admin/checkins/{checkIn}/force-checkout', [AdminController::class, 'forceCheckout'])->name('admin.checkins.force-checkout');
        Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
        Route::post('/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
        Route::get('/admin/reviews', [AdminController::class, 'reviews'])->name('admin.reviews');
        Route::post('/admin/reviews/{review}/approve', [AdminController::class, 'approveReview'])->name('admin.reviews.approve');
        Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
        Route::get('/admin/reports/export/{type}', [AdminController::class, 'exportReport'])->name('admin.reports.export');
        Route::get('/admin/payroll', [PayrollController::class, 'index'])->name('admin.payroll.index');
        Route::get('/admin/payroll/create', [PayrollController::class, 'create'])->name('admin.payroll.create');
        Route::post('/admin/payroll', [PayrollController::class, 'store'])->name('admin.payroll.store');
        Route::post('/admin/payroll/generate', [PayrollController::class, 'generateAll'])->name('admin.payroll.generate');
        Route::get('/admin/payroll/{payroll}', [PayrollController::class, 'show'])->name('admin.payroll.show');
        Route::post('/admin/payroll/{payroll}/approve', [PayrollController::class, 'approve'])->name('admin.payroll.approve');
        Route::post('/admin/payroll/{payroll}/paid', [PayrollController::class, 'markPaid'])->name('admin.payroll.paid');
        Route::delete('/admin/payroll/{payroll}', [PayrollController::class, 'destroy'])->name('admin.payroll.destroy');
        Route::get('/admin/payroll/{payroll}/payslip', [PayrollController::class, 'downloadPayslip'])->name('admin.payroll.payslip');
        Route::get('/admin/audit-logs', [AuditLogController::class, 'index'])->name('admin.audit-logs');
        Route::get('/admin/quick-links', [QuickLinkController::class, 'index'])->name('admin.quick-links.index');
        Route::get('/admin/quick-links/create', [QuickLinkController::class, 'create'])->name('admin.quick-links.create');
        Route::post('/admin/quick-links', [QuickLinkController::class, 'store'])->name('admin.quick-links.store');
        Route::get('/admin/quick-links/{quickLink}/edit', [QuickLinkController::class, 'edit'])->name('admin.quick-links.edit');
        Route::put('/admin/quick-links/{quickLink}', [QuickLinkController::class, 'update'])->name('admin.quick-links.update');
        Route::delete('/admin/quick-links/{quickLink}', [QuickLinkController::class, 'destroy'])->name('admin.quick-links.destroy');
    });

    Route::middleware('role:manager,admin')->group(function () {
        Route::get('/manager', [ManagerController::class, 'dashboard'])->name('manager.dashboard');
        Route::get('/manager/occupancy', [ManagerController::class, 'occupancy'])->name('manager.occupancy');
        Route::get('/manager/reviews', [ManagerController::class, 'reviews'])->name('manager.reviews');
        Route::post('/manager/reviews/{review}/approve', [ManagerController::class, 'approveReview'])->name('manager.reviews.approve');
        Route::post('/manager/reviews/{review}/destroy', [ManagerController::class, 'destroyReview'])->name('manager.reviews.destroy');
        Route::get('/manager/reports', [ManagerController::class, 'reports'])->name('manager.reports');
        Route::get('/manager/reports/export/{type}', [ManagerController::class, 'exportReport'])->name('manager.reports.export');
        Route::get('/manager/audit-logs', [AuditLogController::class, 'managerIndex'])->name('manager.audit-logs');
    });

    Route::middleware('role:receptionist,admin')->group(function () {
        Route::get('/receptionist', [ReceptionistController::class, 'dashboard'])->name('receptionist.dashboard');
        Route::get('/receptionist/search', [ReceptionistController::class, 'searchBooking'])->name('receptionist.search');
        Route::get('/receptionist/walk-in', [ReceptionistController::class, 'walkInCreate'])->name('receptionist.walk-in');
        Route::post('/receptionist/walk-in', [ReceptionistController::class, 'walkInStore'])->name('receptionist.walk-in.store');
        Route::post('/receptionist/bookings/{booking}/approve', [ReceptionistController::class, 'approveBooking'])->name('receptionist.approve');
        Route::post('/receptionist/bookings/{booking}/reject', [ReceptionistController::class, 'rejectBooking'])->name('receptionist.reject');
        Route::post('/receptionist/bookings/{booking}/checkin', [ReceptionistController::class, 'processCheckIn'])->name('receptionist.checkin');
        Route::post('/receptionist/bookings/{booking}/checkout', [ReceptionistController::class, 'processCheckOut'])->name('receptionist.checkout');
        Route::post('/bookings/{booking}/checkin', [CheckInController::class, 'checkIn'])->name('checkin.process');
        Route::post('/bookings/{booking}/checkout', [CheckInController::class, 'checkOut'])->name('checkout.process');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/bank-details', [PayrollController::class, 'employeeDetails'])->name('profile.bank-details');
    Route::post('/profile/bank-details', [PayrollController::class, 'updateEmployeeDetails']);
});

require __DIR__.'/auth.php';
