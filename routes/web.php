<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::controller(PaymentController::class)->group(function () {
    // stripe url-s
    Route::get('checkout','checkoutPage')->name('checkout');
    Route::post('payment-process','paymentProcess')->name('payment-process');
    Route::post('stripe-process','stripeProcess')->name('stripe-process');

    // payu url-s
    Route::get('pay-u-money-view','payUMoneyView');
    Route::post('pay-u-response','payUResponse')->name('pay-u-response');
    Route::post('pay-u-cancel','payUCancel')->name('pay-u-cancel');

    // razorpay url-s
    Route::post('razorpay-payment',  'razorpayStore')->name('razorpay.payment.store');

    // paypal payment url-s
    Route::get('paypal/payment',  'payment')->name('paypal.payment');
    Route::get('paypal/payment/success',  'paymentPaypalSuccess')->name('paypal.payment.success');
    Route::get('paypal/payment/cancel',  'paymentPaypalCancel')->name('paypal.payment/cancel');
});

require __DIR__.'/auth.php';
