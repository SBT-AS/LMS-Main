<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $certificate = $user->certificates()
                            ->with('course')
                            ->findOrFail($id);

        return view('frontend.certificate-show', compact('certificate'));
    }

    public function download($id)
    {
        $user = Auth::user();
        $certificate = $user->certificates()
                            ->with('course')
                            ->findOrFail($id);

        $pdf = Pdf::loadView('frontend.certificate-pdf', compact('certificate'));

        return $pdf->setPaper('a3', 'landscape')
                   ->download('certificate-' . $certificate->certificate_number . '.pdf');
    }
}
