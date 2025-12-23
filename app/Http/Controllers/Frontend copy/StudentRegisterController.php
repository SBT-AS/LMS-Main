<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

class StudentRegisterController extends Controller
{


    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Assign 'student' role automatically
            $role = Role::where('name', 'student')
                ->where('guard_name', 'web')
                ->first();
            
            if (!$role) {
                $role = Role::create([
                    'name' => 'student',
                    'guard_name' => 'web'
                ]);
            }
            
            $user->assignRole($role);

            DB::commit();
            Mail::to($user->email)->send(new WelcomeMail($user));

            event(new Registered($user));

            Auth::login($user);

            // Breeze compatible redirect
            return redirect()->route('student.dashboard')
                ->with('status', 'Registration successful! Welcome to your dashboard.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage());
            
            return back()
                ->withInput($request->only('name', 'email'))
                ->withErrors(['registration' => 'Registration failed. Please try again.']);
        }
    }
}