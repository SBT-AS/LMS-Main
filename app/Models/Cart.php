<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    protected $fillable = ['user_id', 'course_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public static function isInCart($courseId)
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            return self::where('user_id', \Illuminate\Support\Facades\Auth::id())
                ->where('course_id', $courseId)
                ->exists();
        } else {
            $cart = session()->get('cart', []);
            return isset($cart[$courseId]);
        }
    }
}
