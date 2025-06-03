<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ExplorerController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductOrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StoreController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
// section routes
Route::get("/section", [DashboardController::class, 'section'])->name("section");
Route::post("/saveCookie", [DashboardController::class, 'saveCookie'])->name("saveCookie");
Route::post("/saveSession", [DashboardController::class, 'saveSession'])->name("saveSession");
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard routes
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard'); 
    Route::get('/booking', [DashboardController::class, 'booking'])->name('booking');
    Route::get('/organizers', [DashboardController::class, 'organizers'])->name('organizers');
    Route::get('/places', [DashboardController::class, 'places'])->name('places'); 

    // Event Routes
    Route::get('/events', [EventController::class, 'index'])->name('events.app');
    Route::get('/event/create', [EventController::class, 'create'])->name('event.create');
    Route::post('/events', [EventController::class, 'store'])->name('event.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    // Events by Category
    Route::get('/events-by-category', [EventController::class, 'eventsByCategory'])->name('events.by.cat');
    Route::get('/events-by-category/filter', [EventController::class, 'getEventsByCategory'])->name('events.filter.by.categories');

    // Events by Organizer
    Route::get('/events-by-organizer', [EventController::class, 'eventsByOrganizer'])->name('events.by.org');
    Route::get('/events-by-organizer/{organizer}', [EventController::class, 'getEventsByOrganizer'])->name('events.filter.by.organizer');
    Route::get('/events/export', [EventController::class, 'export'])->name('events.export');
    Route::post('/events/import', [EventController::class, 'import'])->name('events.import');
    Route::get('/api/dashboard/charts', [EventController::class, 'getChartData'])->name('dashboard.charts');
    Route::get('/products/print', [EventController::class, 'print'])->name('events.print');

    // Explorer search API routes
    Route::prefix('api/explorers')->group(function () {
        Route::get('/search', [ExplorerController::class, 'search'])->name('explorers.search');
        Route::get('/search-term/{term}', [ExplorerController::class, 'searchTerm'])->name('explorers.search.term');
    });

    // Explorer booking API route
    Route::get('/api/explorers/{explorer}/bookings', [BookingController::class, 'getExplorerBookings'])->name('explorers.bookings');

    // Booking routes
    Route::prefix('bookings')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('booking.index');
        Route::put('/{booking}/status', [BookingController::class, 'updateStatus'])->name('booking.update.status');
        Route::get('/{booking}/details', [BookingController::class, 'getBookingDetails'])->name('booking.details');
    });
});

// Cookie/Session routes (public)
Route::get("/section", [DashboardController::class, 'section'])->name("section");
Route::post("/saveCookie", [DashboardController::class, 'saveCookie'])->name("saveCookie");
Route::post("/saveSession", [DashboardController::class, 'saveSession'])->name("saveSession");

// Eloquent routes
Route::get('/ordered-events', [ProductOrderController::class, 'index'])->name('ordered.events');
Route::get('/same-events-explorers', [CustomerController::class, 'sameeventsexplorers'])->name('same.events.explorers');
Route::get('events/booking-count', [ProductController::class, 'bookingCount'])->name('events.booking_count');
Route::get('/events-more-than-6-booking', [ProductController::class, 'eventsMoreThan6booking'])->name('events.more_than_6_booking');
Route::get('/order-totals', [OrderController::class, 'orderTotals'])->name('booking.totals');
Route::get('/booking-greater-than-60', [OrderController::class, 'bookingGreaterThanOrder60'])->name('booking.greater_than_60');

// Solution exercise requetes eloquent
Route::get('/ex/booking', [StoreController::class, 'ex_booking'])->name('ex.booking');
Route::get('/organizers/events', [StoreController::class, 'organizers_events'])->name('suppliers.events');
Route::get('events/same_place', [StoreController::class, 'events_same_place'])->name('events.same_place');
Route::get('/events/countbystore', [StoreController::class, 'countbystore'])->name('events.countbystore');
Route::get('/store/value', [StoreController::class, 'storeValue'])->name('store.value');
Route::get('/sotre/greater_than_lind', [StoreController::class, 'storeGreater_than_lind'])->name('sotre.greater_than_lind');
