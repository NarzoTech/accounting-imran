<?php

use Illuminate\Support\Facades\Route;
use Modules\Website\app\Http\Controllers\BlogController;
use Modules\Website\app\Http\Controllers\CheckoutController;
use Modules\Website\app\Http\Controllers\User\PropertyController;
use Modules\Website\app\Http\Controllers\User\UserController;
use Modules\Website\app\Http\Controllers\UtilityController;
use Modules\Website\app\Http\Controllers\WebsiteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['as' => 'website.', 'middleware' => ['translation', 'maintenance.mode']], function () {
    Route::get('/', [WebsiteController::class, 'home'])->name('home');
    Route::get('property', [WebsiteController::class, 'property'])->name('property');
    Route::get('property-details/{slug}', [WebsiteController::class, 'propertyDetails'])->name('property-details');
    Route::get('about-us', [WebsiteController::class, 'aboutUs'])->name('about-us');
    Route::get('contact-us', [WebsiteController::class, 'contactUs'])->name('contact-us');
    Route::get('blogs', [BlogController::class, 'index'])->name('blogs');
    Route::get('blog-details/{slug}', [BlogController::class, 'details'])->name('blog-details');
    Route::get('pricing-plan', [UtilityController::class, 'pricingPlan'])->name('pricing-plan');
    Route::get('checkout', [CheckoutController::class, 'checkout'])->middleware('auth')->name('checkout');
    Route::get('checkout-success', [CheckoutController::class, 'success'])->name('checkout-success');
    Route::get('checkout-failed', [CheckoutController::class, 'failed'])->name('order-fail');
    Route::post('property-contact-message', [WebsiteController::class, 'propertyContactMessage'])->name('property.contact.message');

    Route::get('agents', [WebsiteController::class, 'agents'])->name('agents');
    Route::get('agent-details/{slug}', [WebsiteController::class, 'agentDetails'])->name('agent-details');

    Route::get('faqs', [UtilityController::class, 'faqs'])->name('faqs');
    Route::get('page/{slug}', [UtilityController::class, 'page'])->name('page');


    Route::group(['middleware' => 'auth'], function () {
        Route::name('user.')->prefix('user')->controller(UserController::class)->group(function () {

            // get routes
            Route::get('dashboard', 'dashboard')->name('dashboard');
            Route::post('/upload/user/avatar', 'uploadAvatar')->name('upload.user.avatar');
            Route::put('update-password', 'updatePassword')->name('update.password');
            Route::get('profile', 'profile')->name('profile');
            Route::put('update-profile', 'updateProfile')->name('update.profile');
            Route::resource('property', PropertyController::class)->names('property');
            Route::get('pricing-plan', 'pricingPlan')->name('pricing.plan');
            Route::get('reviews', 'reviews')->name('reviews');
            Route::get('/orders', 'order')->name('order');
            Route::get('/wishlist', 'wishlist')->name('wishlist');
            Route::post('/wishlist/store', 'storeWishlist')->name('wishlist.store');
            Route::get('wishlist/delete/{id}', 'deleteWishlist')->name('wishlist.delete');

            Route::get('/invoice/{id}', 'invoice')->name('invoice');

            Route::post('review/store', 'storeReview')->name('review.store');

            // post routes
            Route::post('/address', 'storeAddress')->name('address.store');
        });
    });
});
