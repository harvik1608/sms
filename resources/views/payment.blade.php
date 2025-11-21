<!DOCTYPE html>
<html>
<head>
    <title>Razorpay Payment</title>
</head>
<body>

<button id="rzp-button">Pay Now</button>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
var options = {
    "key": "{{ $key }}",
    "amount": "{{ $amount }}",
    "currency": "INR",
    "name": "Test Payment",
    "description": "Demo Payment",
    "order_id": "{{ $orderId }}",
    "handler": function (response){
        fetch("{{ route('payment.verify') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify(response)
        })
        .then(res => res.text())
        .then(alert);
    }
};

var rzp = new Razorpay(options);

document.getElementById('rzp-button').onclick = function(e){
    rzp.open();
    e.preventDefault();
}
</script>

</body>
</html>
