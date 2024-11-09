<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Razorpay Gateway</title>
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>

  <div>payment is processing...</div>
  <a href="{{ route('checkout') }}" id="goBack" style="display:none;">Go Back</a>
  <button id="rzp-button1" style="display:none;">Pay</button>
  <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <script>
    var options = { 
      "key": "{{ $key }}",
      "amount": "{{ $order['amount'] }}",
      "currency": "{{ $order['currency'] }}",    
      "name": "KT Razorpay",
      "description": "Test Transaction",
      "image": "https://example.com/your_logo",
      "order_id": "{{ $order['id'] }}",
      "callback_url": "{{ route('razorpay.payment.store') }}",
      "prefill": {
        "name": "Piyush Shyam",
        "email": "piyush@demo.com",
        "contact": "1234567891" 
      },
      "modal": {
          "ondismiss": function(){
            // Add your code here to handle modal close event
            window.location.replace("{{ route('checkout') }}");
          }
      },
      "notes": {
        "address": "KT Corporate Office" 
      },
      "theme": {
        "color": "#3399cc" 
      }
      
    };
    var rzp1 = new Razorpay(options);
    document.getElementById('rzp-button1').onclick = function(e){ 
      rzp1.open();    
      e.preventDefault();
    }
    
    document.getElementById('rzp-button1').click();
   
   
  </script>
  </body>
</html>