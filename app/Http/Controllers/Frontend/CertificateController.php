<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $certificates = $user->certificates()->with('course')->get();

        return view('frontend.certificates-index', compact('certificates'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $certificate = $user->certificates()->with('course')->findOrFail($id);

        return view('frontend.certificate-show', compact('certificate'));
    }
}
