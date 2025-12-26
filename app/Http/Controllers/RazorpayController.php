<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\Course;
use Exception;

class RazorpayController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $cartItems = Cart::with('course')->where('user_id', $user->id)->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->course->price;
        });

        // Razorpay API Setup
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        
        $orderData = [
            'receipt'         => 'rcptid_' . time(),
            'amount'          => $total * 100, // Amount in paise
            'currency'        => 'INR',
            'payment_capture' => 1 // Auto capture
        ];
        
        try {
            $razorpayOrder = $api->order->create($orderData);
            $orderId = $razorpayOrder['id'];
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error creating Razorpay order: ' . $e->getMessage());
        }

        return view('razorpay.checkout', compact('cartItems', 'total', 'orderId'));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        
        $paymentId = $input['razorpay_payment_id'];
        $orderId = $input['razorpay_order_id'];
        $signature = $input['razorpay_signature'];

        try {
            $attributes = array(
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature
            );
            
            $api->utility->verifyPaymentSignature($attributes);
            
            // Payment Successful - Enroll User
            $user = Auth::user();

            // Save Payment History
            $paymentInfo = $api->payment->fetch($paymentId);
            \App\Models\Payment::create([
                'user_id' => $user->id,
                'rwp_payment_id' => $paymentId,
                'rwp_order_id' => $orderId,
                'amount' => $paymentInfo->amount / 100, // Convert back to main unit
                'status' => 'success',
            ]);

            $cartItems = Cart::with('course')->where('user_id', $user->id)->get();
            
            foreach ($cartItems as $item) {
                // Enroll user in course if not already enrolled
                if (!$user->courses->contains($item->course_id)) {
                    $user->courses()->attach($item->course_id, [
                        'enrolled_at' => now(),
                        'status' => 'active'
                    ]);
                    
                    // Generate certificate logic if needed immediately or upon completion
                    // $this->generateCertificate($user->id, $item->course_id); 
                }
            }
            
            // Clear Cart
            Cart::where('user_id', $user->id)->delete();
            
            return redirect()->route('student.dashboard')->with('success', 'Payment successful! You have been enrolled in the courses.');

        } catch (Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }
}
