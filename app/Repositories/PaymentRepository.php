<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Interfaces\PaymentRepositoryInterface;
use Stripe;
use Razorpay\Api\Api;
use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentRepository implements PaymentRepositoryInterface
{
    /** 
     * Exception Function
     * @param exceptionObj
     * @param functionName
     * @return logException
    */
    protected function logException(Exception $e,$functionName){
        $message = $e->getMessage();
        $code = $e->getCode();       
        $string = $e->__toString();  

        Log::error('Function name: ' . $functionName . ' Class name :' .  class_basename(__CLASS__) . ' ,Exception Message: '. $message . ' ,Exception Code: '. $code . ' ,Exception String: ' . $string);
        return;
    }


    /** 
     * Stripe Payment Gateway Function
     * @param requestObj
     * @return booleanVal
    */
    public function stripePaymentGateway(Request $request)
    {
        try{
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $customer = [
                'name' => $request->firstname . ' ' . $request->lastname,
                "email" => $request->email,
                "source" => $request->stripeToken,
                "address" => ["city" => $request->city, "country" => $request->country, "line1" => "adsafd werew", "postal_code" => $request->zip, "state" => $request->state],
            ];

            $customerData = Stripe\Customer::create($customer);

            $charge = Stripe\Charge::create(array(
                'customer' => $customerData->id,
                'amount' => $request->price * 100,
                'currency' => 'inr',
                'description' => "Test payment from itsolutionstuff.com.",
            ));

            if ($charge->status === "succeeded") {
                Order::insert([
                    "txn_id" => $charge->id,
                    "status" => "completed",
                    "fullname" =>auth()->user()->name,
                    "address" => $request->city . ', ' . $request->country . " ,adsafd werew, " . $request->zip . ", " . $request->state,
                    "bill" => $request->price,
                    "customerId" => auth()->user()->id,
                    "payment_type" => "stripe",
                    "created_at" => date('y-m-d h:i:s'),
                    "updated_at" => date('y-m-d h:i:s'),

                ]);
                return true;
            } else {
                Order::insert([
                    "txn_id" => $charge->id,
                    "status" => "failed",
                    "fullname" => auth()->user()->name,
                    "address" => $request->city . ', ' . $request->country . " ,adsafd werew, " . $request->zip . ", " . $request->state,
                    "bill" => $request->price,
                    "customerId" => auth()->user()->id,
                    "payment_type" => "stripe",
                    "created_at" => date('y-m-d h:i:s'),
                    "updated_at" => date('y-m-d h:i:s'),

                ]);
                return false;
            }
        } catch (Exception $e) {
            $this->logException($e,__FUNCTION__);
            return false;
        }
    }

    /** 
     * payu Payment Gateway Function
     * @param requestObj
     * @return textResWithData
    */
    public function payuPaymentGateway(Request $request)
    {
        try{
            $MERCHANT_KEY = env('PAYU_MERCHANT_KEY'); // TEST MERCHANT KEY
            $SALT = env('PAYU_SALT'); // TEST SALT
    
            $PAYU_BASE_URL = env('PAYU_BASE_URL_TEST');
            
            $name = $request->firstname . ' ' . $request->lastname;
            $address1 = $request->city . ', ' . $request->country . " ,adsafd werew, " . $request->zip . ", " . $request->state;
            
            $successURL = route('pay-u-response');
            $failURL = route('pay-u-cancel');
            $email = $request->email;
            // $amount = $request->price;
            $amount = rand(100,555);
            $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
            
            $hash = strtolower(hash('sha512', "$MERCHANT_KEY|$txnid|$amount|testing|$name|$email|||||||||||$SALT"));
            $action = $PAYU_BASE_URL . '/_payment';
    
            return $response = [
                'action' =>  $action,
                'hash' =>  $hash,
                'MERCHANT_KEY' =>  $MERCHANT_KEY,
                'txnid' =>  $txnid,
                'successURL' =>  $successURL,
                'failURL' =>  $failURL,
                'name' =>  $name,
                'email' =>  $email,
                'amount' =>  $amount,
                'address1' => $address1
            ];
        }   catch (Exception $e) {
            $this->logException($e,__FUNCTION__);
        }
        
        return [];
    }

    /** 
     * Payu Payment Gateway Response save Function
     * @param requestObj
     * @param paymentStatus
     * @return booleanVal
    */
    public function payUResponseSave(Request $request,$status)
    {
        
        $output = false;
        try{
            $paymentStatus = $status;
            $orderTableStatus = 'failed';
            if($paymentStatus == "success")
            {
                $output = true;
                $orderTableStatus = "completed";
            }
    
            Order::insert([
                "txn_id" => $request->txnid,
                "status" => $orderTableStatus,
                "fullname" => auth()->user()->name,
                "address" => $request->address1,
                "bill" => $request->amount,
                "customerId" => auth()->user()->id,
                "payment_type" => $request->payment_source,
                "created_at" => date('y-m-d h:i:s'),
                "updated_at" => date('y-m-d h:i:s'),
    
            ]);
            $output = true;
        }catch (Exception $e) {
            $this->logException($e,__FUNCTION__);
        }

        return $output;
    }

    /** 
     * razorpay payment capture function
     * @param request
     * @return response
    */
    public function razorpayCheckout()
    {
        $response = [];
        try{
            $pay_data= array();
            $key = env('RAZORPAY_KEY');
            $secret = env('RAZORPAY_SECRET');
            $api = new Api($key,$secret);
            $price = rand(111,999);
            
            $order_id = rand(111,999);
            
            $order = $api->order->create([
                'receipt' => 'order_rcpt_id_'.$order_id, 
                'amount' => $price * 100, 
                'currency' => 'INR'
            ]);

           
            $pay_data['razorpay_order_id'] = $order['id'];
            $pay_data['customerId'] = auth()->user()->id;
            $pay_data['bill'] = $price;
            $pay_data['status'] = $order['status'] == 'created' ? 'pending' : 'failed';
            $pay_data['currency'] = $order['currency'];
            $pay_data['checkout_order_id'] = $order['receipt'];
            $pay_data['payment_type'] = "razorpay";
            $pay_data['fullname'] = auth()->user()->name;
            $pay_data['address'] =  'Nagpur, India, adsafd werew, 440014, Maharashtra';
            $pay_data['created_at'] = date('y-m-d h:i:s');
            $pay_data['updated_at'] = date('y-m-d h:i:s');

            Order::insert([$pay_data]);
            return ['order'=>$order,'key'=>$key,'secret'=>$secret];
        } catch (Exception $e) {
            $this->logException($e,__FUNCTION__);
        }
        return $response;

    }

    /** 
     * razorpay payment success function
     * @param request
     * @return response
    */
    public function razorpayPaymentGateway(Request $request)
    {
        $response =  false;
        try{
            $data = array();
            $secret = env('RAZORPAY_SECRET');
            $order_payment_id = $request->razorpay_order_id . "|" . $request->razorpay_payment_id;
            $generated_signature = hash_hmac("sha256",$order_payment_id, $secret);
    
            $data['txn_id'] = isset($request->razorpay_payment_id) ? $request->razorpay_payment_id : null;
            $data['razorpay_order_id'] = isset($request->razorpay_order_id) ? $request->razorpay_order_id : null;
            $data['razorpay_signature'] = isset($request->razorpay_signature) ? $request->razorpay_signature : null;  
    
            if ($generated_signature == $request->razorpay_signature) {
                $data['status'] = 'completed'; 
                $response = true;
            } else {
                $data['status'] = 'failed';
                $response = false; 
            }
            
            Order::where('razorpay_order_id',$request->razorpay_order_id)->update($data);
        } catch (Exception $e) {
            $this->logException($e,__FUNCTION__);
        }
       
        return $response;
    }


    /** 
     * paypal payment process function
     * @param request
     * @return response
    */
    public function paypalPaymentProcess()
    {
        try{
            $pay_data = array();
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $paypalToken = $provider->getAccessToken();
            $price = rand(11,99);
            
            $order_id = rand(111,999);
            $checkout_order_id = 'order_rcpt_id_'.$order_id;
      
            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('paypal.payment.success'),
                    "cancel_url" => route('paypal.payment/cancel'),
                ],
                "purchase_units" => [
                    0 => [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => $price
                        ]
                    ]
                ]
            ]);

            if (isset($response['id']) && $response['id'] != null) {
                $pay_data['razorpay_order_id'] = $response['id'];
                $pay_data['customerId'] = auth()->user()->id;
                $pay_data['bill'] = $price;
                $pay_data['status'] = $response['status'] == 'CREATED' ? 'pending' : 'failed';
                $pay_data['currency'] = "USD";
                $pay_data['checkout_order_id'] = $checkout_order_id;
                $pay_data['payment_type'] = "paypal";
                $pay_data['fullname'] = auth()->user()->name;
                $pay_data['address'] =  'Nagpur, India, adsafd werew, 440014, Maharashtra';
                $pay_data['created_at'] = date('y-m-d h:i:s');
                $pay_data['updated_at'] = date('y-m-d h:i:s');
                Order::insert([$pay_data]);
                return $response;
            } else {
                return false;
            }

        }  catch (Exception $e) {
            $this->logException($e,__FUNCTION__);
        }
        return false;
    }

    /** 
     * paypal payment success function
     * @param request
     * @return booleanVal
    */
    public function paypalPaymentSuccess(Request $request)
    {
        try{
            $response = $this->getPaypalCapturedResponse($request);
           
            if (isset($response['status']) && $response['status'] != null && $response['status'] == "COMPLETED" && isset($response['purchase_units']) && $response['purchase_units'] != null ) {
                $txnId = $response['purchase_units'][0]['payments']['captures'][0]['id'];
                Order::where('razorpay_order_id',$response['id'])->update(['status' => 'completed','txn_id' => $txnId]);
                return true;
            } else {
                return false;
            }
        }  catch (Exception $e) {
            $this->logException($e,__FUNCTION__);
        }
        return false;
    }

    /** 
     * paypal payment cancel function
     * @param request
     * @return booleanVal
    */
    public function paypalPaymentCancel(Request $request)
    {
        try{
            $response = $this->getPaypalCapturedResponse($request);
            if (isset($response['error']) && $response['error'] != null) {
                Order::where('razorpay_order_id',$request['token'])->update(['status' => 'failed']);
                return false;
            } else {
                return false;
            }
        }  catch (Exception $e) {
            $this->logException($e,__FUNCTION__);
        }
        return false;
    }

    /** 
     * paypal payment captured response function
     * @param request
     * @return response
    */
    protected function getPaypalCapturedResponse(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        return $response;
    }
}
