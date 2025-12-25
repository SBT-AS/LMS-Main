<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $cartItems = Cart::with('course')->where('user_id', Auth::id())->get();
            $courses = $cartItems->pluck('course')->filter();
        } else {
            $cart = Session::get('cart', []);
            $courses = Course::whereIn('id', array_keys($cart))->get();
        }
        
        $total = $courses->sum('price');
        
        return view('frontend.cart', compact('courses', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id'
        ]);

        $course = Course::findOrFail($request->course_id);
        
        // Check if user is already enrolled
        if (Auth::check() && Auth::user()->courses->contains($course->id)) {
            return redirect()->back()->with('info', 'You are already enrolled in this course.');
        }

        if (Auth::check()) {
            $exists = Cart::where('user_id', Auth::id())->where('course_id', $course->id)->exists();
            if ($exists) {
                return redirect()->back()->with('info', 'Course is already in your cart.');
            }

            Cart::create([
                'user_id' => Auth::id(),
                'course_id' => $course->id,
            ]);
        } else {
            $cart = Session::get('cart', []);
            
            // Check if already in cart
            if (isset($cart[$course->id])) {
                return redirect()->back()->with('info', 'Course is already in your cart.');
            }

            $cart[$course->id] = [
                'title' => $course->title,
                'price' => $course->price,
            ];

            Session::put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Course added to cart!');
    }

    public function destroy($id)
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->where('course_id', $id)->delete();
        } else {
            $cart = Session::get('cart', []);

            if (isset($cart[$id])) {
                unset($cart[$id]);
                Session::put('cart', $cart);
            }
        }

        return redirect()->back()->with('success', 'Course removed from cart.');
    }
}
