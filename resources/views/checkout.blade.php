@extends('layouts.layout')

@section('title', 'Checkout')

@section('main')
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <div class="container">
        <div class="py-4 text-center">
            <h2>Checkout form</h2>
        </div>

        @if (Session::has('success'))
            <div class="alert alert-success text-center hidemessage">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif

        @if (Session::has('error'))
            <div class="alert alert-danger text-center hidemessage">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                <p>{{ Session::get('error') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('payment-process') }}">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <h4 class="mb-3">Billing address</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName">First name</label>
                            <input type="text" class="form-control" name="firstname" id="firstName" value="{{ auth()->user()->name }}" required readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName">Last name</label>
                            <input type="text" class="form-control" name="lastname" id="lastName" value="ipsum" required readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" value="{{ auth()->user()->email }}"
                            required readonly>
                    </div>

                    <div class="mb-3">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" value="1234 Main St" required>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="country">Country</label>
                            <select class="custom-select d-block w-100" id="country" name="country" required>
                                <option>India</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="state">State</label>
                            <select class="custom-select d-block w-100" id="state" name="state" required>
                                <option>Maharashtra</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="city">city</label>
                            <select class="custom-select d-block w-100" id="city" name="city" required>
                                <option>Nagpur</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="zip">Zip</label>
                            <input type="text" class="form-control" id="zip" name="zip" value="440014">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Your cart</span>
                        <span class="badge badge-secondary badge-pill">1</span>
                    </h4>
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0">T-Shirt</h6>
                                <small class="text-muted">Nice Cotton T-shirt</small>
                            </div>
                            <span class="text-muted">200 INR</span>
                            <input type="hidden" name="price" value="200" id="price" />
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total (INR)</span>
                            <strong>200 INR</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between" style="border-bottom: none">
                            <h4>Payment</h4>
                        </li>
                        <li class="list-group-item d-flex" style="border-top: none">
                            <ul class = "list-unstyled">
                                <li>
                                    <div class="d-block">
                                        <div class="custom-control custom-radio">
                                            <input id="paypal" name="payment_type" value="paypal" type="radio"
                                                class="custom-control-input" required>
                                            <label class="custom-control-label" for="paypal">PayPal</label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-block">
                                        <div class="custom-control custom-radio">
                                            <input id="razorpay" name="payment_type" value="razorpay" type="radio"
                                                class="custom-control-input" required>
                                            <label class="custom-control-label" for="razorpay">Razorpay</label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-block">
                                        <div class="custom-control custom-radio">
                                            <input id="payu" name="payment_type" value="payu" type="radio"
                                                class="custom-control-input" required>
                                            <label class="custom-control-label" for="payu">Payu</label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-block">
                                        <div class="custom-control custom-radio">
                                            <input id="stripe" name="payment_type" value="stripe" name="stripe"
                                                type="radio" class="custom-control-input" required>
                                            <label class="custom-control-label" for="stripe">Stripe</label>
                                        </div>
                                    </div>
                                </li>
                            </ul>



                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <button class="btn btn-primary btn-lg btn-block" type="submit">Continue to
                                checkout</button>
                        </li>
                    </ul>
                </div>
            </div>
        </form>
    </div>
    
@endsection

@section('scripts')
    <script>
        let milliseconds = 5000;

        setTimeout(function() {
            $('.hidemessage').remove();
        }, milliseconds);
    </script>
@endsection
