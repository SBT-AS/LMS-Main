<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cart;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('course')
            ->where('user_id', Auth::id())
            ->get();
            
        $total = $cartItems->sum(function($item) {
            return $item->course->price;
        });

        return view('frontend.cart', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $courseId = $request->course_id;
        $userId = Auth::id();

        // Check if course already in cart
        $exists = Cart::where('user_id', $userId)->where('course_id', $courseId)->exists();
        
        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Course is already in your cart.']);
        }

        Cart::create([
            'user_id' => $userId,
            'course_id' => $courseId
        ]);

        $count = Cart::where('user_id', $userId)->count();

        return response()->json([
            'success' => true, 
            'message' => 'Course added to cart successfully!',
            'cart_count' => $count
        ]);
    }

    public function destroy($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->where('id', $id)->first();
        
        if ($cartItem) {
            $cartItem->delete();
            return back()->with('success', 'Item removed from cart.');
        }

        return back()->with('error', 'Item not found in cart.');
    }
}
