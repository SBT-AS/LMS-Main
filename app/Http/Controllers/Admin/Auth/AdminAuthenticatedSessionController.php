<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminAuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('backend.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        
        // If user is admin, go to admin dashboard
        if ($request->user()->hasRole('admin')) {
             return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        // If user tries to log in here but is NOT admin,
        // we can either redirect them to student dashboard or log them out and say "Admins Only".
        // For a better UX, redirecting to their appropriate dashboard is usually fine, 
        // OR strict separation:
        
        // Let's redirect to student Dashboard if they are a student, to avoid confusion.
        return redirect()->intended(route('student.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
