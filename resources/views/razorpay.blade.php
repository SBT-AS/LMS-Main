<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Payment | LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0c1117;
            color: #c9d1d9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            margin: 0;
            overflow: hidden;
        }

        .payment-container {
            text-align: center;
            background: rgba(22, 27, 34, 0.8);
            backdrop-filter: blur(10px);
            padding: 3rem;
            border-radius: 20px;
            border: 1px solid rgba(48, 54, 61, 0.5);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            max-width: 400px;
            width: 90%;
            position: relative;
        }

        .loader {
            width: 80px;
            height: 80px;
            border: 5px solid rgba(16, 185, 129, 0.1);
            border-top: 5px solid #10b981;
            border-radius: 50%;
            display: inline-block;
            animation: spin 1s linear infinite;
            margin-bottom: 2rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        h2 {
            font-weight: 700;
            margin-bottom: 1rem;
            color: #ffffff;
        }

        p {
            color: #8b949e;
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        #razor-pay-button {
            display: none;
        }
        
        /* Subtle glow effect */
        .payment-container::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #10b981, transparent, #10b981);
            z-index: -1;
            border-radius: 22px;
            opacity: 0.3;
            filter: blur(5px);
        }
    </style>
</head>
<body>

    <div class="payment-container">
        <div class="loader"></div>
        <h2>Initializing Secure Payment</h2>
        <p>Please do not refresh the page or close the window. We are redirecting you to our secure payment gateway.</p>
        
        <form action="{{ route('razorpay.callback') }}" method="POST">
            @csrf
            <script src="https://checkout.razorpay.com/v1/checkout.js"
                    data-key="{{ config('services.razorpay.key') }}"
                    data-amount="{{ $order->amount }}"
                    data-currency="{{ $order->currency }}"
                    data-order_id="{{ $order->id }}"
                    data-buttontext="Pay with Razorpay"
                    data-name="LMS Platform"
                    data-description="Course Purchase"
                    data-image="https://cdn.razorpay.com/logos/gh_original.png"
                    data-prefill.name="{{ auth()->user()->name }}"
                    data-prefill.email="{{ auth()->user()->email }}"
                    data-theme.color="#10b981">
            </script>
        </form>
    </div>

    <script>
        window.onload = function() {
            // Trigger Razorpay payment automatically
            const razorpayButton = document.querySelector('.razorpay-payment-button');
            if (razorpayButton) {
                razorpayButton.click();
            }
        };
    </script>
</body>
</html>
