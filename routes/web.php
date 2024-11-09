<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialiteController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// socialite login
Route::prefix('auth')->name('auth.')->controller(SocialiteController::class)->group(function () {
    // google url
    Route::get('google','googleLogin')->name('google');
    Route::get('google-callback','googleAuthentication')->name('google-callback');
});


Route::middleware(['auth', 'verified'])->group(function(){

    Route::controller(PaymentController::class)->group(function () {
        
        Route::get('checkout','checkoutPage')->name('checkout');
        Route::post('payment-process','paymentProcess')->name('payment-process');

        // stripe url-s
        Route::post('stripe-process','stripeProcess')->name('stripe-process');

        // payu url-s
        Route::get('pay-u-money-view','payUMoneyView');
        Route::post('pay-u-response','payUResponse')->name('pay-u-response');
        Route::get('pay-u-cancel','payUCancel')->name('pay-u-cancel');

        // razorpay url-s
        Route::post('razorpay-payment',  'razorpayStore')->name('razorpay.payment.store');

        // paypal payment url-s
        Route::get('paypal/payment',  'payment')->name('paypal.payment');
        Route::get('paypal/payment/success',  'paymentPaypalSuccess')->name('paypal.payment.success');
        Route::get('paypal/payment/cancel',  'paymentPaypalCancel')->name('paypal.payment/cancel');
    });
});


require __DIR__.'/auth.php';
