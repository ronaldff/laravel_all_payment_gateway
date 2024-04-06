<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\PaymentRepositoryInterface;

class PaymentController extends Controller
{

    private PaymentRepositoryInterface $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository) 
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * checkout method.
     * @return checkoutViewPage
     */
    public function checkoutPage(){
        return view('checkout');
    }

    /**
     * payment process method.
     * @param request
     * @return processPayment
     * @return viewPage
     */
    public function paymentProcess(Request $request)
    {
        $data = $this->buildData($request);

        switch ($request->payment_type) {
            case 'stripe':
                return view('stripe',compact('data'));
                break;

            case 'payu':
                $response = $this->paymentRepository->payuPaymentGateway($request);
                if(!empty($response)){
                    $action = $response['action'];
                    $hash = $response['hash'];
                    $MERCHANT_KEY = $response['MERCHANT_KEY'];
                    $txnid = $response['txnid'];
                    $successURL = $response['successURL'];
                    $failURL = $response['failURL'];
                    $name = $response['name'];
                    $email = $response['email'];
                    $amount = $response['amount'];
                    $address1 = $response['address1'];
    
                    return view('pay-u',compact('action','hash','MERCHANT_KEY','txnid','successURL','failURL','name','email','amount','address1'));
                } else {
                    return $this->redirectTocheckoutPage(false);
                }
             
                break;
            
            case 'razorpay':
                $response = $this->paymentRepository->razorpayCheckout();
                if(!empty($response)){
                    return view('razorpay-view',$response);
                } else {
                    return $this->redirectTocheckoutPage(false);
                }
                break;

            case 'paypal':
                $response = $this->paymentRepository->paypalPaymentProcess();
                if (isset($response['id']) && $response['id'] != null) {
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }
          
                    return redirect()->route('cancel.payment');
                } else {
                    return $this->redirectTocheckoutPage(false);
                }
                return redirect()->route('paypal.payment');
                break;

            default:
                return redirect()->route('checkout')->with('error', "This $request->payment_type payment gateway service is not present in our system");
                break;
        }
    }

    /**
     * stripe payment process method.
     * @param request
     * @return view
     */
    public function stripeProcess(Request $request)
    {
        $response = $this->paymentRepository->stripePaymentGateway($request);
        return $this->redirectTocheckoutPage($response);
    }
    
    /**
     * payu response payment process method.
     * @param request
     * @return view
     */
    public function payUResponse(Request $request)
    {
        $response = $this->paymentRepository->payUResponseSave($request,$request->status);
        return $this->redirectTocheckoutPage($response);
    }

    /**
     * payu cancel payment process method.
     * @param request
     * @return view
     */
    public function payUCancel(Request $request)
    {
        $response = $this->paymentRepository->payUResponseSave($request,$request->status);
        return $this->redirectTocheckoutPage($response);
    }

    /**
     * razorpay payment process method.
     *
     * @return response()
     */
    public function razorpayStore(Request $request)
    {
        $response = $this->paymentRepository->razorpayPaymentGateway($request);
        return $this->redirectTocheckoutPage($response);
    }

    /**
     * redirection method.
     * @param boolean
     * @return view
     */
    public function redirectTocheckoutPage($response){
        if ($response) {
            return redirect()->route('checkout')->with('success', 'Order Placed Successfully');
        } else {
            return redirect()->route('checkout')->with('error', 'Something Went Wrong');
        }
    }

    /**
     * building data method.
     * @return requestDataArray
     */
    protected function buildData(Request $request)
    {
        return [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'zip' => $request->zip,
            'price' => $request->price,
            'payment_type' => $request->payment_type,
        ];
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function paymentPaypalCancel(Request $request)
    {
        $response = $this->paymentRepository->paypalPaymentCancel($request);
        return $this->redirectTocheckoutPage($response);
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function paymentPaypalSuccess(Request $request)
    {
        $response = $this->paymentRepository->paypalPaymentSuccess($request);
        return $this->redirectTocheckoutPage($response);
    }

}
