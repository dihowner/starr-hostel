<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\PaystackController;
use App\Http\Controllers\ShoppingCartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect to login page if Route does not exists...
Route::fallback(function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    if (Auth::user()) {
        return redirect()->route('home');
    }
    return view('login');
})->name('login');

Route::get('/register', function () {

    if (Auth::user()) {
        return redirect()->route('home');
    }

    return view('register');
})->name('register');

Route::controller(UserController::class)->group(function() {
    Route::post('process-register', 'RegisterAccount')->name('process-register');
    Route::post('process-login', 'LoginAccount')->name('process-login');
    Route::get('/logout', 'logOut')->name('logout');
});

Route::controller(HotelController::class)->group(function() {
    Route::get("/", "index")->name('home');

    Route::get('view-hostel/{id}', 'viewHostel')->name('view-hostel');
});

Route::middleware(['auth'])->group(function () {
    Route::controller(ShoppingCartController::class)->group(function () {
        Route::get('/view-cart', 'viewCart')->name('view-cart'); // From view-hotel
        Route::post('/add-room-cart', 'AddRoomCart')->name('add-room-cart'); // From view-hotel
        Route::get('/add-cart/{id}', 'AddRoomCart'); //From anywhere
        Route::get('/delete-cart/{id}', 'DeleteCart')->name('delete-cart');

        Route::post("create-payment-shopping", 'CheckOutCart')->name('create-payment-shopping');
    });
    
    Route::controller(PaystackController::class)->group(function () {
        Route::get('/approve-paystack-payment', 'approvePayment')->name('approve-paystack-payment');
    });
});