<?php

namespace App\Interfaces;

use Illuminate\Http\Request;


interface PaymentRepositoryInterface {

  public function stripePaymentGateway(Request $request);

  public function payuPaymentGateway(Request $request);

}

?>