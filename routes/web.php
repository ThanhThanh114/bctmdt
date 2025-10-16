<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
;

use App\Http\Controllers\BookingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\AuthController;


// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('password.verify-otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('password.verify-otp.post');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.store');

// Admin Routes
Route::get('/admin', function () {
    return redirect('/AdminLTE/index.php');
})->name('admin');

// Trip routes
Route::get('/lichtrinh', [TripController::class, 'index'])->name('trips.trips');
Route::post('/lichtrinh', [TripController::class, 'handleSearch'])->name('trips.search');

// Legacy routes for backward compatibility
Route::get('/LichTrinh.php', [TripController::class, 'index']);
Route::get('/TraCuu.php', [TrackingController::class, 'index']);
Route::get('/TinTuc.php', [NewsController::class, 'index']);
Route::get('/HoaDon.php', [InvoiceController::class, 'index']);
Route::get('/LienHe.php', [ContactController::class, 'index']);
Route::get('/About.php', [PageController::class, 'index']);

//Tracking routes
Route::get('/tracuu', [TrackingController::class, 'index'])->name('tracking.tracking');
Route::post('/tracuu', [TrackingController::class, 'search'])->name('tracking.search');
// News routes
Route::get('/tintuc', [NewsController::class, 'index'])->name('news.news');
Route::get('/tintuc/{id}', [NewsController::class, 'show'])->name('news.show');

// Invoice
Route::get('/hoadon', [InvoiceController::class, 'index'])->name('invoice.index');
Route::post('/hoadon/check', [InvoiceController::class, 'check'])->name('invoice.check');
Route::get('/hoadon/show', [InvoiceController::class, 'show'])->name('invoice.show');

// Home routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/index.php', [HomeController::class, 'index']);

// Booking routes
Route::get('/datve', [BookingController::class, 'index'])->name('booking.booking');
Route::get('/datve/{id}', [BookingController::class, 'show'])->name('booking.show');
Route::post('/datve', [BookingController::class, 'store'])->name('booking.store');
Route::get('/chon-ghe', [BookingController::class, 'seatSelection'])->name('booking.seat_selection');
Route::post('/hoan-tat-dat-ve', [BookingController::class, 'completeBooking'])->name('booking.complete');
Route::post('/api/check-discount', [BookingController::class, 'checkDiscount'])->name('api.check-discount');

// Payment routes
Route::get('/thanh-toan/{code}', [BookingController::class, 'showPayment'])->name('payment.show');
Route::post('/xac-nhan-thanh-toan', [BookingController::class, 'verifyPayment'])->name('payment.verify');

Route::get('/dat-ve-thanh-cong/{code}', [BookingController::class, 'success'])->name('booking.success');

// Ticket <routes></routes>
Route::get('/search', [TicketController::class, 'search']);
Route::post('/search', [TicketController::class, 'search'])->name('search.ticket');
Route::get('/schedule', [TicketController::class, 'schedule'])->name('schedule.index');

// Contact routes
Route::get('/lienhe', [ContactController::class, 'index'])->name('contact.contact');
Route::post('/lienhe/guiyc', [ContactController::class, 'send'])->name('contact.send');

// User Profile routes
Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
Route::get('/profile/edit', [AuthController::class, 'editProfile'])->name('profile.edit');
Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

// Password Management routes
Route::get('/password/edit', [AuthController::class, 'editPassword'])->name('password.edit');
Route::put('/password', [AuthController::class, 'updatePassword'])->name('password.update');

// Booking History routes
Route::get('/booking-history', [BookingController::class, 'history'])->name('booking.history');

//Page controllers
Route::get('/about', [PageController::class, 'index'])->name('about.about');

// Role-based Dashboard Routes
Route::middleware(['auth'])->group(function () {
    // Admin Dashboard Routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Quản lý người dùng
        Route::resource('users', App\Http\Controllers\Admin\UsersController::class);

        // Quản lý nhân viên
        Route::resource('nhanvien', App\Http\Controllers\Admin\NhanVienController::class);

        // Quản lý đặt vé
        Route::resource('datve', App\Http\Controllers\Admin\DatVeController::class)->except(['edit']);
        Route::patch('datve/{datve}/status', [App\Http\Controllers\Admin\DatVeController::class, 'updateStatus'])->name('datve.update-status');
        Route::post('datve/{datve}/cancel', [App\Http\Controllers\Admin\DatVeController::class, 'cancel'])->name('datve.cancel');
        Route::post('dat-ve/{id}/xac-nhan-thanh-toan', [App\Http\Controllers\Admin\DatVeController::class, 'confirmPayment'])->name('datve.confirm-payment');
        Route::get('datve-statistics', [App\Http\Controllers\Admin\DatVeController::class, 'statistics'])->name('datve.statistics');
        Route::get('datve-export', [App\Http\Controllers\Admin\DatVeController::class, 'export'])->name('datve.export');
        Route::post('datve-auto-cancel', [App\Http\Controllers\Admin\DatVeController::class, 'autoCancelExpired'])->name('datve.auto-cancel');

        // Quản lý bình luận
        Route::resource('binhluan', App\Http\Controllers\Admin\BinhLuanController::class)->except(['edit', 'update']);
        Route::post('binhluan/{binhluan}/approve', [App\Http\Controllers\Admin\BinhLuanController::class, 'approve'])->name('binhluan.approve');
        Route::post('binhluan/{binhluan}/reject', [App\Http\Controllers\Admin\BinhLuanController::class, 'reject'])->name('binhluan.reject');
        Route::post('binhluan/{binhluan}/reply', [App\Http\Controllers\Admin\BinhLuanController::class, 'reply'])->name('binhluan.reply');
        Route::post('binhluan/{binhluan}/toggle-lock', [App\Http\Controllers\Admin\BinhLuanController::class, 'toggleLock'])->name('binhluan.toggle-lock');
        Route::post('binhluan/bulk-approve', [App\Http\Controllers\Admin\BinhLuanController::class, 'bulkApprove'])->name('binhluan.bulk-approve');
        Route::post('binhluan/bulk-delete', [App\Http\Controllers\Admin\BinhLuanController::class, 'bulkDelete'])->name('binhluan.bulk-delete');
        Route::get('binhluan-statistics', [App\Http\Controllers\Admin\BinhLuanController::class, 'statistics'])->name('binhluan.statistics');

        // Quản lý doanh thu
        Route::get('doanhthu', [App\Http\Controllers\Admin\DoanhThuController::class, 'index'])->name('doanhthu.index');
        Route::get('doanhthu/by-trip', [App\Http\Controllers\Admin\DoanhThuController::class, 'byTrip'])->name('doanhthu.by-trip');
        Route::get('doanhthu/by-company', [App\Http\Controllers\Admin\DoanhThuController::class, 'byCompany'])->name('doanhthu.by-company');
        Route::get('doanhthu/export', [App\Http\Controllers\Admin\DoanhThuController::class, 'export'])->name('doanhthu.export');

        // Quản lý khuyến mãi
        Route::resource('khuyenmai', App\Http\Controllers\Admin\KhuyenMaiController::class);
        Route::post('khuyenmai/{khuyenmai}/toggle-status', [App\Http\Controllers\Admin\KhuyenMaiController::class, 'toggleStatus'])->name('khuyenmai.toggle-status');
        Route::post('khuyenmai/check-code', [App\Http\Controllers\Admin\KhuyenMaiController::class, 'checkCode'])->name('khuyenmai.check-code');

        // Quản lý tin tức
        Route::resource('tintuc', App\Http\Controllers\Admin\TinTucController::class);
        Route::post('tintuc/bulk-delete', [App\Http\Controllers\Admin\TinTucController::class, 'bulkDelete'])->name('tintuc.bulk-delete');
        Route::post('tintuc/{tintuc}/toggle-pin', [App\Http\Controllers\Admin\TinTucController::class, 'togglePin'])->name('tintuc.toggle-pin');
        Route::post('tintuc/{tintuc}/toggle-publish', [App\Http\Controllers\Admin\TinTucController::class, 'togglePublish'])->name('tintuc.toggle-publish');

        // Quản lý liên hệ
        Route::get('contact', [App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contact.index');
        Route::get('contact/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contact.show');
        Route::delete('contact/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contact.destroy');
        Route::post('contact/bulk-delete', [App\Http\Controllers\Admin\ContactController::class, 'bulkDelete'])->name('contact.bulk-delete');
        Route::get('contact-export', [App\Http\Controllers\Admin\ContactController::class, 'export'])->name('contact.export');

        // Quản lý báo cáo
        Route::get('report', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('report.index');
        Route::get('report/bookings', [App\Http\Controllers\Admin\ReportController::class, 'bookings'])->name('report.bookings');
        Route::get('report/revenue', [App\Http\Controllers\Admin\ReportController::class, 'revenue'])->name('report.revenue');
        Route::get('report/users', [App\Http\Controllers\Admin\ReportController::class, 'users'])->name('report.users');
        Route::get('report/comments', [App\Http\Controllers\Admin\ReportController::class, 'comments'])->name('report.comments');
        Route::get('report/operators', [App\Http\Controllers\Admin\ReportController::class, 'operators'])->name('report.operators');
        Route::get('report/routes', [App\Http\Controllers\Admin\ReportController::class, 'routes'])->name('report.routes');
        Route::get('report/contacts', [App\Http\Controllers\Admin\ReportController::class, 'contacts'])->name('report.contacts');
        Route::get('report/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('report.export');
    });

    // Staff Dashboard Routes
    Route::prefix('staff')->name('staff.')->middleware('role:staff')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('bookings', App\Http\Controllers\Staff\BookingsController::class)->except(['create', 'store', 'edit']);
        Route::patch('bookings/{booking}/status', [App\Http\Controllers\Staff\BookingsController::class, 'updateStatus'])->name('bookings.update-status');
        Route::get('bookings-today', [App\Http\Controllers\Staff\BookingsController::class, 'todayBookings'])->name('bookings.today');
        Route::get('bookings-pending', [App\Http\Controllers\Staff\BookingsController::class, 'pendingBookings'])->name('bookings.pending');
        // Add more staff routes here as needed
    });

    // Bus Owner Dashboard Routes
    Route::prefix('bus-owner')->name('bus-owner.')->middleware('role:bus_owner')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\BusOwner\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('trips', App\Http\Controllers\BusOwner\TripsController::class);
        // Add more bus owner routes here as needed
    });

    // User Dashboard Routes
    Route::prefix('user')->name('user.')->middleware('role:user')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('bookings', App\Http\Controllers\User\BookingsController::class)->except(['edit', 'update']);
        Route::patch('bookings/{booking}/cancel', [App\Http\Controllers\User\BookingsController::class, 'cancel'])->name('bookings.cancel');
        // Add more user routes here as needed
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
