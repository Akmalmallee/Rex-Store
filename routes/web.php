<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ShopController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\WishlistController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\PaymentAccountController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/category/{slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.detail');

// Debug route - temporary
Route::get('/debug/products', function () {
    $products = \App\Models\Product::select('id', 'name', 'thumbnail', 'updated_at')->limit(5)->get();
    $storageDir = storage_path('app/public/products');
    $publicStorageLink = public_path('storage');
    
    return response()->json([
        'products' => $products,
        'storage_dir' => $storageDir,
        'storage_dir_exists' => is_dir($storageDir),
        'public_storage_link' => $publicStorageLink,
        'public_storage_is_link' => is_link($publicStorageLink),
        'public_storage_is_dir' => is_dir($publicStorageLink),
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::get('/cart/sidebar', [CartController::class, 'sidebar'])->name('cart.sidebar');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/payment/{orderId}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/payment/{orderId}/upload', [CheckoutController::class, 'uploadPayment'])->name('payment.upload');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/review/store', [ProductController::class, 'reviewStore'])->name('review.store');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('/products', AdminProductController::class);
    Route::post('/products/{id}/restore', [AdminProductController::class, 'restore'])->name('products.restore');

    Route::resource('/categories', CategoryController::class);
    Route::resource('/brands', BrandController::class);
    Route::resource('/users', UserController::class);
    Route::resource('/orders', AdminOrderController::class);
    Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/orders/{id}/export-pdf', [AdminOrderController::class, 'exportPdf'])->name('orders.export-pdf');

    Route::resource('/payments', PaymentController::class);
    Route::post('/payments/{id}/approve', [PaymentController::class, 'approve'])->name('payments.approve');
    Route::post('/payments/{id}/reject', [PaymentController::class, 'reject'])->name('payments.reject');

    Route::resource('/payment-accounts', PaymentAccountController::class);

    Route::resource('/reviews', ReviewController::class);
    Route::resource('/coupons', CouponController::class);
    Route::resource('/banners', BannerController::class);
    Route::resource('/promos', PromoController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
