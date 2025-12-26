@extends('frontend.layouts.app')

@section('title', 'Secure Checkout')

@section('content')
<style>
    .checkout-section {
        background-color: #f8f9fa;
        min-height: 80vh;
        display: flex;
        align-items: center;
    }
    .checkout-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        background: #ffffff;
    }
    .checkout-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        text-align: center;
        position: relative;
    }
    .checkout-header h2 {
        font-weight: 700;
        font-size: 1.8rem;
        margin-bottom: 5px;
    }
    .checkout-header p {
        opacity: 0.9;
        font-size: 0.95rem;
        margin-bottom: 0;
    }
    .order-summary {
        background-color: #f8faff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        border: 1px solid #e1e4e8;
    }
    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        color: #555;
        font-size: 0.95rem;
    }
    .summary-item:last-child {
        margin-bottom: 0;
    }
    .total-row {
        border-top: 2px dashed #d1d5db;
        margin-top: 15px;
        padding-top: 15px;
        font-weight: 700;
        font-size: 1.2rem;
        color: #2d3748;
    }
    .pay-btn {
        background-color: #3399cc; /* Razorpay Blue */
        border: none;
        padding: 15px 30px;
        font-size: 1.1rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .pay-btn:hover {
        background-color: #2b82ad;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(51, 153, 204, 0.4);
    }
    .trust-badges {
        margin-top: 20px;
        font-size: 0.8rem;
        color: #718096;
    }
    .trust-badges i {
        color: #48bb78;
        margin-right: 5px;
    }
</style>

<section class="checkout-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card checkout-card">
                    <!-- Header -->
                    <div class="checkout-header">
                        <h2><i class="fas fa-lock me-2"></i>Secure Checkout</h2>
                        <p>Complete your purchase safely</p>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <!-- Order Summary -->
                        <h5 class="fw-bold mb-3 text-dark">Order Summary</h5>
                        <div class="order-summary">
                            @foreach($cartItems as $item)
                                <div class="summary-item">
                                    <span>{{ Str::limit($item->course->title, 25) }}</span>
                                    <span class="fw-semibold">₹{{ number_format($item->course->price, 2) }}</span>
                                </div>
                            @endforeach
                            
                            <!-- Discount/Tax logic could go here in future -->
                            
                            <div class="summary-item total-row d-flex justify-content-between align-items-center">
                                <span>Total Amount</span>
                                <span class="text-primary">₹{{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <!-- User Info (Optional Visual) -->
                        <div class="mb-4 text-muted small">
                            <p class="mb-1"><i class="fas fa-user-circle me-2"></i>Paying as: <strong>{{ Auth::user()->name }}</strong></p>
                            <p class="mb-0"><i class="fas fa-envelope me-2"></i>{{ Auth::user()->email }}</p>
                        </div>

                        <!-- Pay Button -->
                        <button id="rzp-button1" class="btn btn-primary btn-lg w-100 rounded-pill pay-btn text-white">
                            Pay Now <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                        
                        <!-- Trust Indicators -->
                        <div class="text-center trust-badges">
                            <span class="mx-2"><i class="fas fa-shield-alt"></i> 100% Secure</span>
                            <span class="mx-2"><i class="fas fa-check-circle"></i> Encrypted Payment</span>
                        </div>

                        <!-- Hidden Form -->
                        <form action="{{ route('razorpay.payment.store') }}" method="POST" id="razorpay-form" style="display: none;">
                            @csrf
                            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                            <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
                            <input type="hidden" name="razorpay_signature" id="razorpay_signature">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Razorpay Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    var options = {
        "key": "{{ config('services.razorpay.key') }}", 
        "amount": "{{ $total * 100 }}", 
        "currency": "INR",
        "name": "{{ config('app.name') }}",
        "description": "Premium Course Purchase",
        "image": "{{ asset('logo.png') }}",
        "order_id": "{{ $orderId }}", 
        "handler": function (response){
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
            document.getElementById('razorpay_signature').value = response.razorpay_signature;
            document.getElementById('razorpay-form').submit();
        },
        "prefill": {
            "name": "{{ Auth::user()->name }}",
            "email": "{{ Auth::user()->email }}",
            "contact": "{{ Auth::user()->phone ?? '' }}" 
        },
        "notes": {
            "address": "LMS Platform"
        },
        "theme": {
            "color": "#667eea" 
        },
        "modal": {
            "ondismiss": function(){
                console.log('Checkout form closed');
            }
        }
    };
    
    var rzp1 = new Razorpay(options);
    
    document.getElementById('rzp-button1').onclick = function(e){
        rzp1.open();
        e.preventDefault();
    }
</script>
@endsection
