<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FabricController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\InventoryLogController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\ReviewItemController;
use App\Http\Controllers\ShopReviewController;
use App\Http\Controllers\ReviewItemController as AdminReviewItemController;

Route::get('/', [FabricController::class, 'homepage'])->name('welcome.homepage');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/fabrics', [FabricController::class, 'index'])->name('fabrics.index');

Route::get('/fabrics/{id}/reviews', [ReviewItemController::class, 'reviewsForProduct'])->name('review.index');

Route::middleware(['auth'])->group(function () {

    Route::get('/calendar', [CalendarController::class, 'myCalendar'])->name('calendar.my');
    // Route::get('/calendar/events', [CalendarController::class, 'myEvents'])->name('calendar.events.my');

    // Meeting request (user) removed


    //order

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/booking', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    Route::patch('/orders/{order}/update-price', [OrderController::class, 'updatePrice'])->name('orders.updatePrice');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    //cart

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('add-to-cart/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('remove-from-cart', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('update-cart', [CartController::class, 'updateCart'])->name('cart.update');

    Route::post('/checkout', [OrderController::class, 'storeCart'])->name('orders.storeCart');


    //profile 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllasRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    Route::get('/venues', [VenueController::class, 'index'])->name('venues.index');
    //reviews
    // Submit review baru
   Route::post('/review-items', [ReviewItemController::class, 'store'])->name('review.store');

    // UPDATE review (harus login)
    Route::put('/review-items/{id}', [ReviewItemController::class, 'update'])->name('review.update');

    //review Shop 
    Route::get('/shop/reviews', [ShopReviewController::class, 'index'])->name('shop.reviews');
Route::post('/shop/review', [ShopReviewController::class, 'store'])->name('review.shop.store');
});

// Halaman Admin
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/fabrics/create', [FabricController::class, 'create'])->name('fabrics.create');
    Route::post('/fabrics', [FabricController::class, 'store'])->name('fabrics.store');

    Route::get('/fabrics/{fabric}/edit', [FabricController::class, 'edit'])->name('fabrics.edit');
    Route::put('/fabrics/{fabric}', [FabricController::class, 'update'])->name('fabrics.update');

    // Delete
    Route::delete('/fabrics/{fabric}', [FabricController::class, 'destroy'])->name('fabrics.destroy');

    // Fitur Restock
    Route::get('/fabrics/{fabric}/restock', [FabricController::class, 'editStock'])->name('fabrics.restock');
    Route::post('/fabrics/{fabric}/stock', [FabricController::class, 'updateStock'])->name('fabrics.updateStock');


    Route::get('/inventory-logs', [InventoryLogController::class, 'index'])->name('inventory_logs.index');

    Route::patch('/orders/{order}/approve', [OrderController::class, 'approve'])->name('orders.approve');
    Route::patch('/orders/{order}/reject', [OrderController::class, 'reject'])->name('orders.reject');


    //schedule routes

    Route::get('/users/{meeting}/calendar', [CalendarController::class, 'adminUser'])->name('calendar.user');
    Route::get('/users/{meeting}/calendar/events', [CalendarController::class, 'userEvents'])->name('calendar.events.user');

    //calendar admin
    Route::get('/calendar/admin', [CalendarController::class, 'adminAll'])->name('calendar.admin');




    // Admin meeting request page removed (unused)

    // Product reviews moderation
    Route::get('/admin/product-reviews', [ReviewItemController::class, 'adminIndex'])->name('admin.productReviews.index');
    Route::post('/admin/product-reviews/{review}/approve', [ReviewItemController::class, 'approve'])->name('admin.productReviews.approve');
    Route::post('/admin/product-reviews/{review}/reject', [ReviewItemController::class, 'reject'])->name('admin.productReviews.reject');

    // Shop reviews moderation
    Route::get('/admin/shop-reviews', [ShopReviewController::class, 'adminIndex'])->name('admin.shopReviews.index');
    Route::post('/admin/shop-reviews/{review}/approve', [ShopReviewController::class, 'approve'])->name('admin.shopReviews.approve');
    Route::post('/admin/shop-reviews/{review}/reject', [ShopReviewController::class, 'reject'])->name('admin.shopReviews.reject');

    // Shop reviews moderation
    Route::get('/admin/shop-reviews', [ShopReviewController::class, 'adminIndex'])->name('admin.shopReviews.index');
    Route::post('/admin/shop-reviews/{review}/approve', [ShopReviewController::class, 'approve'])->name('admin.shopReviews.approve');
    Route::post('/admin/shop-reviews/{review}/reject', [ShopReviewController::class, 'reject'])->name('admin.shopReviews.reject');

    // Admin Venues
    Route::get('/admin/venues', [VenueController::class, 'adminIndex'])->name('admin.venues.index');
    Route::get('/admin/venues/create', [VenueController::class, 'create'])->name('admin.venues.create');
    Route::post('/admin/venues', [VenueController::class, 'store'])->name('admin.venues.store');
    Route::get('/admin/venues/{venue}/edit', [VenueController::class, 'edit'])->name('admin.venues.edit');
    Route::put('/admin/venues/{venue}', [VenueController::class, 'update'])->name('admin.venues.update');
    Route::delete('/admin/venues/{venue}', [VenueController::class, 'destroy'])->name('admin.venues.destroy');

    // Admin Broadcast Notifications
    Route::get('/admin/notifications/broadcast', [NotificationController::class, 'broadcastCreate'])->name('admin.notifications.broadcast');
    Route::post('/admin/notifications/broadcast', [NotificationController::class, 'broadcastStore'])->name('admin.notifications.broadcast.store');
});

Route::get('/fabrics/{fabric}', [FabricController::class, 'show'])->name('fabrics.show');

require __DIR__ . '/auth.php';
