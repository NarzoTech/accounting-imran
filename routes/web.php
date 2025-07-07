<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Modules\Order\app\Models\Order;
use Modules\Subscription\app\Models\SubscriptionHistory;



Route::get('/', function () {
    return view('index');
})->name('home');


Route::get('/dashboard', function () {
    return redirect()->route('website.user.dashboard');
})->middleware(['auth:web', 'verified'])->name('dashboard');

Route::get('/invoice/{uuid}/{type?}', function ($uuid, $type = null) {
    if ($type == 'subscription') {
        return SubscriptionHistory::where('uuid', $uuid)->firstOrFail();
    } else {
        return Order::where('uuid', $uuid)->firstOrFail();
    }
})->middleware(['auth:web', 'verified'])->name('user.invoice');

//maintenance mode route
Route::get('/maintenance-mode', function () {
    $setting = Illuminate\Support\Facades\Cache::get('setting', null);
    if (!$setting?->maintenance_mode) {
        return redirect()->route('website.home');
    }

    return view('maintenance');
})->name('maintenance.mode');
Route::get('set-language', [DashboardController::class, 'setLanguage'])->name('set-language');
Route::get('set-currency', [DashboardController::class, 'setCurrency'])->name('set-currency');

Route::middleware('auth:web')->group(function () {
    //Profile route
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/user/update-password', [ProfileController::class, 'update_password'])->name('user.update-password');
});

require __DIR__ . '/auth.php';

require __DIR__ . '/admin.php';

Route::fallback(function () {
    abort(404);
});
