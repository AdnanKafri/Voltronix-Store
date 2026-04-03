<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\DeliveryController as AdminDeliveryController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Language switching
Route::get('/locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('locale.switch');

// Currency switching (GET route like language switcher)
Route::get('/currency/{currency}', function ($currency) {
    if (\App\Services\CurrencyService::setCurrency($currency)) {
        return redirect()->back()->with('success', __('admin.currency.currency_switched'));
    }
    return redirect()->back()->with('error', __('admin.currency.invalid_currency'));
})->name('currency.switch.get');

// Currency switching (supports both AJAX and form submissions)
Route::post('/currency/switch', function (Illuminate\Http\Request $request) {
    $currencyCode = $request->input('currency');
    
    if (\App\Services\CurrencyService::setCurrency($currencyCode)) {
        // If it's an AJAX request, return JSON
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('admin.currency.currency_switched'),
                'currency' => $currencyCode
            ]);
        }
        
        // For form submissions, redirect back with success message
        return redirect()->back()->with('success', __('admin.currency.currency_switched'));
    }
    
    // Handle failure
    if ($request->expectsJson() || $request->wantsJson()) {
        return response()->json([
            'success' => false,
            'message' => __('admin.currency.invalid_currency')
        ], 400);
    }
    
    return redirect()->back()->with('error', __('admin.currency.invalid_currency'));
})->name('currency.switch');

// Public routes with locale middleware
Route::middleware(['setlocale'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Public routes (no authentication required)
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/api/products/search', [ProductController::class, 'search'])->name('products.search');

    // Product Reviews routes (TODO: Implement ProductReviewController)
    // Route::middleware('auth')->group(function () {
    //     Route::patch('/products/{product}/reviews/{review}', [ProductReviewController::class, 'update'])->name('products.reviews.update');
    //     Route::delete('/products/{product}/reviews/{review}', [ProductReviewController::class, 'destroy'])->name('products.reviews.delete');
    // });
    Route::get('/products/{product:slug}/reviews', [ProductController::class, 'loadReviews'])->name('products.reviews.load');
    
    // Special Offers
Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');
Route::post('/offers/apply-coupon', [OfferController::class, 'applyCoupon'])->name('offers.apply-coupon');

// Search
Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search.index');
Route::get('/api/search', [App\Http\Controllers\SearchController::class, 'ajax'])->name('search.ajax');

    // AJAX routes
    Route::get('/api/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('/api/cart/summary', [CartController::class, 'summary'])->name('cart.summary');
    Route::get('/api/currency/rate/{code}', function($code) {
        $currency = \App\Models\Currency::where('code', $code)->where('is_active', true)->first();
        if ($currency) {
            return response()->json([
                'success' => true,
                'rate' => $currency->exchange_rate,
                'symbol' => $currency->symbol
            ]);
        }
        return response()->json(['success' => false], 404);
    })->name('api.currency.rate');

    // Cart routes (accessible to guests and authenticated users)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/validate', [CartController::class, 'validate'])->name('cart.validate');
});

// Protected routes (require authentication but not email verification)
Route::middleware(['auth', 'setlocale'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Product review routes
    Route::post('/products/{product:slug}/reviews', [ProductController::class, 'storeReview'])->name('products.reviews.store');
    Route::patch('/products/{product:slug}/reviews/{review}', [ProductController::class, 'updateReview'])->name('products.reviews.update');
    Route::delete('/products/{product:slug}/reviews/{review}', [ProductController::class, 'deleteReview'])->name('products.reviews.delete');
});

// TODO: Re-enable email verification check once mail service is configured
// Temporarily using only 'auth' middleware for checkout to allow testing without email verification
// Original: Route::middleware(['auth', 'verified'])->group(function () {
Route::middleware(['auth', 'setlocale'])->group(function () {
    // Checkout routes (temporarily without email verification)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order:order_number}', [CheckoutController::class, 'success'])->name('checkout.success');
});

// Protected routes (require authentication AND email verification)
// TODO: Re-enable email verification for these routes once mail service is configured
// For now, using only 'auth' middleware to maintain consistency during testing
Route::middleware(['auth', 'setlocale'])->group(function () {
    // Order routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order:order_number}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order:order_number}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order:order_number}/receipt/download', [OrderController::class, 'downloadReceipt'])->name('orders.receipt.download');
    Route::get('/orders/{order:order_number}/receipt/view', [OrderController::class, 'viewReceipt'])->name('orders.receipt.view');
    
    // Secure download routes (legacy - will be replaced by new delivery system)
    Route::get('/orders/{order:order_number}/download/{token}', [OrderController::class, 'download'])->name('orders.download');
    Route::post('/orders/{order:order_number}/toggle-credentials', [OrderController::class, 'toggleCredentials'])->name('orders.toggle-credentials');
    
    // New Delivery System Routes
    Route::get('/delivery/download/{token}', [DeliveryController::class, 'download'])->name('delivery.download');
    Route::get('/delivery/credentials/{token}', [DeliveryController::class, 'credentials'])->name('delivery.credentials');
    Route::post('/delivery/reveal/{token}', [DeliveryController::class, 'revealCredentials'])->name('delivery.reveal');
    Route::get('/delivery/request/{token}', [DeliveryController::class, 'requestAccess'])->name('delivery.request');
    Route::post('/delivery/request/{token}', [DeliveryController::class, 'submitAccessRequest'])->name('delivery.request.submit');
    
    // Dashboard (if needed)
    Route::get('/dashboard', function () {
        return redirect()->route('profile.edit');
    })->name('dashboard');
});

// Admin Authentication Routes (with locale middleware)
Route::prefix('admin')->name('admin.')->middleware(['setlocale'])->group(function () {
    Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
});

// Admin root redirect (with locale middleware)
Route::get('/admin', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('admin.login');
})->middleware('setlocale');

// Protected Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth:admin', 'setlocale'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index']);
    
    // Products Management
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::post('products/bulk-action', [\App\Http\Controllers\Admin\ProductController::class, 'bulkAction'])->name('products.bulk-action');
    Route::post('products/validate-field', [\App\Http\Controllers\Admin\ProductController::class, 'validateField'])->name('products.validate-field');
    
    // Product Media Management
    Route::get('products/media/{media}', [\App\Http\Controllers\Admin\ProductController::class, 'getMedia'])->name('products.media.get');
    Route::post('products/media', [\App\Http\Controllers\Admin\ProductController::class, 'storeMedia'])->name('products.media.store');
    Route::post('products/media/{media}', [\App\Http\Controllers\Admin\ProductController::class, 'updateMedia'])->name('products.media.update');
    Route::delete('products/media/{media}', [\App\Http\Controllers\Admin\ProductController::class, 'deleteMedia'])->name('products.media.delete');
    
    // Categories Management
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::post('categories/{category}/toggle-status', [\App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    
    // Coupons Management
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
    Route::post('coupons/{coupon}/toggle-status', [\App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
    Route::post('coupons/generate-code', [\App\Http\Controllers\Admin\CouponController::class, 'generateCode'])->name('coupons.generate-code');
    Route::post('coupons/validate', [\App\Http\Controllers\Admin\CouponController::class, 'validateCoupon'])->name('coupons.validate');
    
    // Currencies Management
    Route::resource('currencies', \App\Http\Controllers\Admin\CurrencyController::class);
    Route::post('currencies/{currency}/toggle-status', [\App\Http\Controllers\Admin\CurrencyController::class, 'toggleStatus'])->name('currencies.toggle-status');
    Route::post('currencies/{currency}/set-default', [\App\Http\Controllers\Admin\CurrencyController::class, 'setDefault'])->name('currencies.set-default');
    Route::post('currencies/{currency}/update-rate', [\App\Http\Controllers\Admin\CurrencyController::class, 'updateRate'])->name('currencies.update-rate');
    Route::post('currencies/update-all-rates', [\App\Http\Controllers\Admin\CurrencyController::class, 'updateAllRates'])->name('currencies.update-all-rates');
    
    // Users Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::get('users/statistics/dashboard', [\App\Http\Controllers\Admin\UserController::class, 'statistics'])->name('users.statistics');
    Route::post('users/check-email', [\App\Http\Controllers\Admin\UserController::class, 'checkEmail'])->name('users.check-email');
    Route::post('users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('users/{user}/suspend', [\App\Http\Controllers\Admin\UserController::class, 'suspend'])->name('users.suspend');
    Route::post('users/{user}/activate', [\App\Http\Controllers\Admin\UserController::class, 'activate'])->name('users.activate');
    Route::post('users/bulk-action', [\App\Http\Controllers\Admin\UserController::class, 'bulkAction'])->name('users.bulk-action');
    
    // Settings Management
    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    
    // Hero Management (integrated into settings)
    Route::post('settings/hero', [\App\Http\Controllers\Admin\SettingsController::class, 'storeHero'])->name('settings.hero.store');
    Route::get('settings/hero/{hero}/edit', [\App\Http\Controllers\Admin\SettingsController::class, 'editHero'])->name('settings.hero.edit');
    Route::put('settings/hero/{hero}', [\App\Http\Controllers\Admin\SettingsController::class, 'updateHero'])->name('settings.hero.update');
    Route::delete('settings/hero/{hero}', [\App\Http\Controllers\Admin\SettingsController::class, 'destroyHero'])->name('settings.hero.destroy');
    Route::post('settings/hero/{hero}/toggle-status', [\App\Http\Controllers\Admin\SettingsController::class, 'toggleHeroStatus'])->name('settings.hero.toggle-status');
    
    // Homepage Sections Management
    Route::resource('homepage-sections', \App\Http\Controllers\Admin\HomepageSectionController::class);
    Route::post('homepage-sections/{homepageSection}/toggle-status', [\App\Http\Controllers\Admin\HomepageSectionController::class, 'toggleStatus'])->name('homepage-sections.toggle-status');
    Route::post('homepage-sections/update-order', [\App\Http\Controllers\Admin\HomepageSectionController::class, 'updateOrder'])->name('homepage-sections.update-order');
    
    
    // Admin Order Management
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/approve', [\App\Http\Controllers\Admin\OrderController::class, 'approve'])->name('orders.approve');
    Route::post('/orders/{order}/reject', [\App\Http\Controllers\Admin\OrderController::class, 'reject'])->name('orders.reject');
    Route::post('/orders/{order}/toggle-downloads', [\App\Http\Controllers\Admin\OrderController::class, 'toggleDownloads'])->name('orders.toggle-downloads');
    Route::post('/orders/{order}/regenerate-tokens', [\App\Http\Controllers\Admin\OrderController::class, 'regenerateTokens'])->name('orders.regenerate-tokens');
    Route::post('/orders/{order}/upload-files', [\App\Http\Controllers\Admin\OrderController::class, 'uploadFiles'])->name('orders.upload-files');
    Route::post('/orders/{order}/update-credentials', [\App\Http\Controllers\Admin\OrderController::class, 'updateCredentials'])->name('orders.update-credentials');
    Route::patch('/orders/{order}/update-status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/add-note', [\App\Http\Controllers\Admin\OrderController::class, 'addNote'])->name('orders.add-note');
    Route::get('/orders/{order}/receipt/download', [\App\Http\Controllers\Admin\OrderController::class, 'downloadReceipt'])->name('orders.receipt.download');
    Route::get('/orders/{order}/receipt/view', [\App\Http\Controllers\Admin\OrderController::class, 'viewReceipt'])->name('orders.receipt.view');
    
    // Order Deliveries Management
    Route::get('/orders/{order}/deliveries', [AdminDeliveryController::class, 'show'])->name('orders.deliveries.show');
    Route::get('/orders/{order}/deliveries/create', [AdminDeliveryController::class, 'create'])->name('orders.deliveries.create');
    Route::post('/orders/{order}/deliveries', [AdminDeliveryController::class, 'store'])->name('orders.deliveries.store');
    Route::get('/orders/{order}/deliveries/{delivery}/edit', [AdminDeliveryController::class, 'edit'])->name('orders.deliveries.edit');
    Route::patch('/orders/{order}/deliveries/{delivery}', [AdminDeliveryController::class, 'update'])->name('orders.deliveries.update');
    Route::delete('/orders/{order}/deliveries/{delivery}', [AdminDeliveryController::class, 'destroy'])->name('orders.deliveries.destroy');
    Route::post('/orders/{order}/deliveries/{delivery}/regenerate-token', [AdminDeliveryController::class, 'regenerateToken'])->name('orders.deliveries.regenerate-token');
    Route::post('/orders/{order}/deliveries/{delivery}/extend', [AdminDeliveryController::class, 'extend'])->name('orders.deliveries.extend');
    Route::post('/orders/{order}/deliveries/{delivery}/revoke', [AdminDeliveryController::class, 'revoke'])->name('orders.deliveries.revoke');
    Route::post('/orders/{order}/deliveries/{delivery}/restore', [AdminDeliveryController::class, 'restore'])->name('orders.deliveries.restore');
    Route::post('/orders/{order}/deliveries/{delivery}/reset-counts', [AdminDeliveryController::class, 'resetCounts'])->name('orders.deliveries.reset-counts');
    Route::get('/orders/{order}/deliveries/{delivery}/logs', [AdminDeliveryController::class, 'logs'])->name('orders.deliveries.logs');
    
    // Global Deliveries Management
    Route::get('/deliveries', [AdminDeliveryController::class, 'index'])->name('deliveries.index');
    Route::post('/deliveries/bulk-action', [AdminDeliveryController::class, 'bulkAction'])->name('deliveries.bulk-action');
    
    // Admin Reviews Management
    Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reject', [\App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/reviews/{review}/reply', [\App\Http\Controllers\Admin\ReviewController::class, 'reply'])->name('reviews.reply');
    Route::delete('/reviews/{review}/reply', [\App\Http\Controllers\Admin\ReviewController::class, 'deleteReply'])->name('reviews.reply.delete');
    Route::post('/reviews/bulk-action', [\App\Http\Controllers\Admin\ReviewController::class, 'bulkAction'])->name('reviews.bulk-action');
    
    // Admin logout is handled by the AuthController above
});

// Public API Routes for AJAX
Route::post('/api/coupons/validate', [\App\Http\Controllers\Admin\CouponController::class, 'validateCoupon'])->name('api.coupons.validate');

// Authentication Routes (Laravel Breeze)
require __DIR__.'/auth.php';

// ✅ Google OAuth Routes
Route::prefix('auth/google')->name('auth.google.')->group(function () {
    Route::get('/', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])
        ->name('redirect');
    Route::get('/callback', [App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback'])
        ->name('callback');
    Route::post('/disconnect', [App\Http\Controllers\Auth\GoogleController::class, 'disconnect'])
        ->name('disconnect')
        ->middleware('auth');
});

// Static Pages
Route::middleware(['setlocale'])->group(function () {
    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
    Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');
    Route::get('/terms', [PageController::class, 'terms'])->name('terms');
    Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
    Route::get('/refund-policy', [PageController::class, 'refund'])->name('refund');
});
