<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\Cart;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RazorpayController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('course')
            ->where('user_id', Auth::id())
            ->get();
            
        $total = $cartItems->sum(function($item) {
            return $item->course->price;
        });

        if ($total <= 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty or the total is 0.');
        }

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        $order = $api->order->create([
            'receipt' => 'order_' . time(),
            'amount' => $total * 100, // Amount in paise
            'currency' => 'INR'
        ]);

        return view('razorpay', compact('order', 'total', 'cartItems'));
    }

    public function paymentCallback(Request $request)
    {
        $input = $request->all();
        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        if (count($input) && !empty($input['razorpay_payment_id'])) {
            try {
                $payment = $api->payment->fetch($input['razorpay_payment_id']);

                if ($payment->status == 'captured' || $request->has('razorpay_signature')) {
                    // Payment successful logic
                    $user = Auth::user();
                    $cartItems = Cart::where('user_id', $user->id)->get();

                    DB::transaction(function () use ($user, $cartItems) {
                        foreach ($cartItems as $item) {
                            // Enroll user in each course
                            if (!$user->enrolledCourses()->where('course_id', $item->course_id)->exists()) {
                                $user->enrolledCourses()->attach($item->course_id, [
                                    'status' => 'active',
                                    'enrolled_at' => now()
                                ]);
                            }
                        }
                        // Clear the cart
                        Cart::where('user_id', $user->id)->delete();
                    });

                    return redirect()->route('student.dashboard')->with('success', 'Payment successful! You have been enrolled in the courses.');
                }
            } catch (\Exception $e) {
                return redirect()->route('cart.index')->with('error', 'Payment failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('cart.index')->with('error', 'Payment failed or was canceled.');
    }
}
