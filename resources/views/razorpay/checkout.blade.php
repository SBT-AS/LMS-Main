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
    
    /* Modal Styles */
    .payment-option-card {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .payment-option-card:hover {
        border-color: #667eea;
        background-color: #f7fafc;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .payment-option-card.active {
        border-color: #667eea;
        background-color: #ebf4ff;
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

                        <button type="button" class="btn btn-primary btn-lg w-100 rounded-pill pay-btn text-white" data-bs-toggle="modal" data-bs-target="#paymentMethodModal">
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

<!-- Payment selection Modal -->
<div class="modal fade" id="paymentMethodModal" tabindex="-1" aria-labelledby="paymentMethodModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-0 bg-light">
        <h5 class="modal-title fw-bold text-dark" id="paymentMethodModalLabel">Select Payment Method</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
          <p class="text-muted mb-4 small">Choose how you would like to complete your purchase of <strong>₹{{ number_format($total, 2) }}</strong>.</p>
          
          <div class="d-grid gap-3">
              <!-- Card / Razorpay Option -->
              <div class="payment-option-card d-flex align-items-center" onclick="proceedWithCard()">
                  <div class="bg-blue-100 rounded-circle p-3 me-3 text-primary bg-light">
                      <i class="fas fa-credit-card fa-lg"></i>
                  </div>
                  <div class="flex-grow-1">
                      <h6 class="fw-bold mb-0 text-dark">Credit / Debit Card</h6>
                      <small class="text-muted">Secure payment via Razorpay</small>
                  </div>
                  <i class="fas fa-chevron-right text-muted"></i>
              </div>

              <!-- UPI Option -->
              <div class="payment-option-card d-flex align-items-center" onclick="proceedWithUPI()">
                   <div class="bg-green-100 rounded-circle p-3 me-3 text-success bg-light">
                      <i class="fas fa-mobile-alt fa-lg"></i>
                  </div>
                  <div class="flex-grow-1">
                      <h6 class="fw-bold mb-0 text-dark">UPI Payment</h6>
                      <small class="text-muted">GPay, PhonePe, Paytm</small>
                  </div>
                  <i class="fas fa-chevron-right text-muted"></i>
              </div>
          </div>

          <!-- UPI Input Form (Hidden initially) -->
          <div id="upi-input-form" style="display: none;">
              <h5 class="fw-bold text-dark mb-3">Enter UPI ID</h5>
              <div class="mb-3">
                  <label for="upi_id" class="form-label text-muted small">UPI ID / VPA</label>
                  <input type="text" class="form-control form-control-lg" id="user_upi_id" name="upi_id" form="dummy-upi-form" placeholder="e.g. user@upi" required>
                  <div id="upi-error-msg" class="text-danger small mt-1" style="display: none;">Invalid UPI ID. Please use success@razorpay or failed.</div>
                  <div class="invalid-feedback">Please enter a valid UPI ID.</div>
              </div>
              <div class="d-grid gap-2">
                  <button type="button" class="btn btn-success btn-lg" onclick="confirmUPIPayment()">
                      Pay ₹{{ number_format($total, 2) }}
                  </button>
                  <button type="button" class="btn btn-outline-secondary" onclick="backToOptions()">Back</button>
              </div>
          </div>

           <!-- UPI Processing Animation (Hidden initially) -->
            <div id="upi-processing-view" class="text-center py-5" style="display: none;">
                <div class="spinner-border text-success mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5 class="fw-bold text-dark mb-2">Processing UPI Payment...</h5>
                <p class="text-muted small">Please do not close this window.</p>
            </div>
      </div>
    </div>
  </div>
</div>

<!-- Dummy UPI Form -->
<form action="{{ route('razorpay.payment.dummy') }}" method="POST" id="dummy-upi-form" style="display: none;">
    @csrf
    <input type="hidden" name="upi_status" id="upi_status">
</form>

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
        "config": {
            "display": {
                "blocks": {
                    "banks": {
                        "name": "Pay via Card",
                        "instruments": [
                            {
                                "method": "card"
                            }
                        ],
                    },
                },
                "sequence": ["block.banks"],
                "preferences": {
                    "show_default_blocks": false,
                },
            },
        },
        "modal": {
            "ondismiss": function(){
                console.log('Checkout form closed');
            }
        }
    };
    
    
    var rzp1 = new Razorpay(options);
    
    // Function to handle Card payment (Real Razorpay)
    function proceedWithCard() {
        // Close modal first
        var myModalEl = document.getElementById('paymentMethodModal');
        var modal = bootstrap.Modal.getInstance(myModalEl);
        modal.hide();
 
        // Open Razorpay
        rzp1.open();
        
    }
 
    // Function to handle UPI payment (Show Input)
    function proceedWithUPI() {
        var optionsDiv = document.querySelector('.modal-body .d-grid');
        var inputForm = document.getElementById('upi-input-form');
        
        // Hide options, show input form
        optionsDiv.style.display = 'none';
        inputForm.style.display = 'block';
    }
 
    // Back to options
    function backToOptions() {
        var optionsDiv = document.querySelector('.modal-body .d-grid');
        var inputForm = document.getElementById('upi-input-form');
        inputForm.style.display = 'none';
        optionsDiv.style.display = 'grid'; // Restore grid layout
    }
 
    // Confirm and Pay
    function confirmUPIPayment() {
        var upiInput = document.getElementById('user_upi_id');
        var errorMsg = document.getElementById('upi-error-msg');
        var upiValue = upiInput.value.trim();

        if(!upiValue) {
            errorMsg.innerText = "Please enter a UPI ID";
            errorMsg.style.display = 'block';
            return;
        }

        if(upiValue === 'success@razorpay') {
            document.getElementById('upi_status').value = 'success';
        } else if(upiValue === 'failed' || upiValue === 'feid') {
            document.getElementById('upi_status').value = 'failed';
        } else {
            errorMsg.innerText = "Please use success@razorpay or failed";
            errorMsg.style.display = 'block';
            upiInput.classList.add('is-invalid');
            return;
        }

        // Reset error message if valid
        errorMsg.style.display = 'none';
        upiInput.classList.remove('is-invalid');

        // Show processing
        var inputForm = document.getElementById('upi-input-form');
        var spinner = document.getElementById('upi-processing-view');
        
        inputForm.style.display = 'none';
        spinner.style.display = 'block';

        // Wait 2 seconds then submit dummy form
        setTimeout(function() {
            document.getElementById('dummy-upi-form').submit();
        }, 2000);
    }

</script>
@endsection
