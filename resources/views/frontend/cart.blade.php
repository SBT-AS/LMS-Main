@extends('frontend.layouts.app')

@section('title', 'Shopping Cart - Educater')

@section('content')
<section class="py-5">
    <div class="container">
        <h1 class="fw-bold mb-5">Shopping Cart</h1>

        @if($courses->count() > 0)
            <div class="row g-4">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    @foreach($courses as $course)
                        <div class="cart-item mb-3 p-3">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    @if($course->image)
                                        <img src="{{ asset('storage/courses/' . $course->image) }}" alt="{{ $course->title }}" class="img-fluid rounded">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=400&q=80" alt="{{ $course->title }}" class="img-fluid rounded">
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h5 class="fw-bold mb-2">{{ $course->title }}</h5>
                                    <p class="text-muted small mb-0">{{ $course->category->name ?? 'General' }}</p>
                                </div>
                                <div class="col-md-3 text-end">
                                    <h5 class="fw-bold mb-3">₹{{ number_format($course->price, 0) }}</h5>
                                    <form action="{{ route('cart.destroy', $course->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="cart-remove-btn btn btn-sm btn-link text-danger">
                                            <i class="bi bi-trash me-1"></i> Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="card-glass p-4 position-sticky" style="top: 100px;">
                        <h4 class="fw-bold mb-4">Order Summary</h4>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span>₹{{ number_format($total, 0) }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tax</span>
                            <span>₹0</span>
                        </div>
                        
                        <hr class="border-secondary border-opacity-10 my-3">
                        
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5 text-accent">₹{{ number_format($total, 0) }}</span>
                        </div>
                        
                        <a href="{{ route('razorpay.index') }}" class="btn btn-primary w-100 py-3 rounded-pill fw-bold">
                            Proceed to Checkout
                        </a>
                        
                        <p class="text-muted small text-center mt-3 mb-0">
                            <i class="bi bi-shield-check me-1"></i> Secure checkout powered by Razorpay
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="card-glass p-5 text-center">
                <i class="bi bi-cart-x fs-1 text-muted mb-3"></i>
                <h3>Your cart is empty</h3>
                <p class="text-muted mb-4">Add some courses to get started!</p>
                <a href="{{ route('frontend.home') }}#courses" class="btn btn-primary rounded-pill px-5">
                    Browse Courses
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
