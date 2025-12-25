@extends('frontend.layouts.app')

@section('title', 'Certificate - ' . $certificate->course->title)

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Great+Vibes&family=Montserrat:wght@300;400;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
<style>
    :root {
        --cert-gold: #c5a059;
        --cert-gold-dark: #8e6d31;
        --cert-emerald: #10b981;
        --cert-bg: #030712;
        --cert-paper: #ffffff;
    }

    .certificate-container {
        padding: 60px 20px;
        background: var(--cert-bg);
        background-image: 
            radial-gradient(circle at 0% 0%, rgba(16, 185, 129, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 100% 100%, rgba(197, 160, 89, 0.05) 0%, transparent 50%);
        min-height: calc(100vh - 80px);
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .certificate-wrapper {
        position: relative;
        width: 100%;
        max-width: 1000px;
        perspective: 1000px;
        animation: certReveal 1.2s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    @keyframes certReveal {
        0% { opacity: 0; transform: translateY(30px) rotateX(5deg); }
        100% { opacity: 1; transform: translateY(0) rotateX(0); }
    }

    .certificate-paper {
        background: var(--cert-paper);
        background-image: 
            url("https://www.transparenttextures.com/patterns/natural-paper.png"),
            radial-gradient(circle at center, rgba(16, 185, 129, 0.02) 0%, transparent 70%);
        color: #1a1e26;
        padding: 80px;
        width: 100%;
        position: relative;
        text-align: center;
        border: 2px solid var(--cert-gold);
        box-shadow: 
            0 30px 60px -12px rgba(0,0,0,0.5),
            0 18px 36px -18px rgba(0,0,0,0.5),
            inset 0 0 0 15px #fff,
            inset 0 0 0 17px var(--cert-gold);
    }

    /* Ornate Corners */
    .cert-corner {
        position: absolute;
        width: 100px;
        height: 100px;
        border: 4px solid var(--cert-gold-dark);
        z-index: 10;
        pointer-events: none;
    }
    .corner-tl { top: 30px; left: 30px; border-bottom: none; border-right: none; }
    .corner-tr { top: 30px; right: 30px; border-bottom: none; border-left: none; }
    .corner-bl { bottom: 30px; left: 30px; border-top: none; border-right: none; }
    .corner-br { bottom: 30px; right: 30px; border-top: none; border-left: none; }

    /* Guilloche Pattern Background */
    .guilloche-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        opacity: 0.04;
        background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M50 50m-40 0a40 40 0 1 0 80 0a40 40 0 1 0 -80 0' fill='none' stroke='%2310b981' stroke-width='0.5'/%3E%3Cpath d='M50 50m-35 0a35 35 0 1 0 70 0a35 35 0 1 0 -70 0' fill='none' stroke='%23c5a059' stroke-width='0.5'/%3E%3C/svg%3E");
        pointer-events: none;
    }

    .cert-header-box {
        margin-bottom: 50px;
    }

    .cert-header {
        font-family: 'Cinzel Decorative', serif;
        font-size: 3.5rem;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 5px;
        letter-spacing: 8px;
        text-transform: uppercase;
        background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .cert-sub-header {
        font-family: 'Montserrat', sans-serif;
        text-transform: uppercase;
        letter-spacing: 6px;
        font-weight: 700;
        color: var(--cert-gold-dark);
        font-size: 1rem;
        position: relative;
        display: inline-block;
        padding: 0 20px;
    }
    .cert-sub-header::before, .cert-sub-header::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 40px;
        height: 1px;
        background: var(--cert-gold);
    }
    .cert-sub-header::before { left: -45px; }
    .cert-sub-header::after { right: -45px; }

    .cert-content {
        font-family: 'Playfair Display', serif;
        margin-bottom: 40px;
    }

    .cert-this-certifies {
        font-size: 1.2rem;
        font-style: italic;
        color: #64748b;
        margin-bottom: 25px;
    }

    .cert-name {
        font-family: 'Great Vibes', cursive;
        font-size: 5.5rem;
        color: #1e293b;
        margin: 15px 0 30px;
        line-height: 1;
        text-shadow: 2px 2px 0 rgba(0,0,0,0.05);
    }

    .cert-completed {
        font-size: 1.1rem;
        color: #64748b;
        margin-bottom: 20px;
    }

    .cert-course-title {
        font-family: 'Cinzel', serif;
        font-size: 2.5rem;
        color: #0f172a;
        margin-bottom: 50px;
        font-weight: 700;
        max-width: 80%;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.2;
    }

    .cert-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 50px;
        padding: 0 40px;
    }

    .signature-block {
        width: 220px;
        text-align: center;
    }

    .signature-line {
        border-top: 2px solid #cbd5e1;
        padding-top: 12px;
        margin-bottom: 5px;
    }

    .signature-name {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.9rem;
        font-weight: 700;
        color: #1e293b;
        text-transform: uppercase;
    }

    .signature-title {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.7rem;
        font-weight: 500;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .cert-seal-wrapper {
        position: relative;
        z-index: 5;
    }

    .cert-seal {
        width: 140px;
        height: 140px;
        background: radial-gradient(circle at 30% 30%, #ffd700, #c5a059);
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #634d10;
        box-shadow: 
            0 10px 20px rgba(0,0,0,0.15),
            inset 0 0 0 5px rgba(255,255,255,0.3),
            inset 0 0 15px rgba(0,0,0,0.2);
        border: 2px solid #b8860b;
        animation: sealPulse 3s infinite ease-in-out;
    }

    @keyframes sealPulse {
        0%, 100% { transform: scale(1); box-shadow: 0 10px 20px rgba(0,0,0,0.15); }
        50% { transform: scale(1.05); box-shadow: 0 15px 30px rgba(0,0,0,0.2), 0 0 20px rgba(197, 160, 89, 0.4); }
    }

    .cert-seal i {
        font-size: 3.5rem;
        margin-bottom: -5px;
    }

    .cert-seal-text {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.6rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .cert-number-box {
        position: absolute;
        bottom: 30px;
        left: 80px;
        right: 80px;
        display: flex;
        justify-content: space-between;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.65rem;
        color: #94a3b8;
        letter-spacing: 1px;
        font-weight: 600;
    }

    .floating-award {
        position: absolute;
        top: -40px;
        right: -40px;
        width: 120px;
        height: 120px;
        background: var(--cert-emerald);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
        border: 8px solid var(--cert-bg);
        z-index: 20;
    }

    /* Print Customizations */
    @media print {
        .no-print { display: none !important; }
        .certificate-container { background: white !important; padding: 0 !important; }
        .certificate-wrapper { max-width: 100% !important; animation: none !important; transform: none !important; }
        .certificate-paper { 
            box-shadow: none !important; 
            border: 2px solid var(--cert-gold) !important; 
            -webkit-print-color-adjust: exact;
        }
        .cert-seal { background: #c5a059 !important; }
    }

    .action-btns {
        display: flex;
        gap: 15px;
        margin-bottom: 35px;
    }

    .btn-premium {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        padding: 10px 24px;
        border-radius: 50px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s;
        text-decoration: none;
    }

    .btn-premium:hover {
        background: rgba(255, 255, 255, 0.1);
        color: var(--cert-emerald);
        transform: translateY(-2px);
    }

    .btn-success-premium {
        background: var(--cert-emerald);
        color: white;
        border: none;
    }

    .btn-success-premium:hover {
        background: #059669;
        color: white;
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
    }
</style>
@endpush

@section('content')
<div class="certificate-container">
    <!-- Action Header -->
    <div class="action-btns no-print">
        <a href="{{ route('student.certificates.index') }}" class="btn-premium">
            <i class="bi bi-grid"></i> All Certificates
        </a>
        <button onclick="window.print()" class="btn-premium btn-success-premium">
            <i class="bi bi-printer-fill"></i> Download / Print
        </button>
    </div>

    <div class="certificate-wrapper">
        <div class="floating-award no-print">
            <i class="bi bi-stars"></i>
        </div>

        <div class="certificate-paper">
            <!-- Background Decoration -->
            <div class="guilloche-overlay"></div>
            <div class="cert-corner corner-tl"></div>
            <div class="cert-corner corner-tr"></div>
            <div class="cert-corner corner-bl"></div>
            <div class="cert-corner corner-br"></div>

            <div class="cert-header-box">
                <div class="cert-header">Certificate</div>
                <div class="cert-sub-header">Official Recognition</div>
            </div>
            
            <div class="cert-content">
                <div class="cert-this-certifies">This prestigious recognition is presented to</div>
                <div class="cert-name">{{ Auth::user()->name }}</div>
                <div class="cert-completed">for excellence and successful completion of the academic program</div>
                <div class="cert-course-title">{{ $certificate->course->title }}</div>
            </div>
            
            <div class="cert-footer">
                <div class="signature-block">
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ $certificate->issued_at->format('M d, Y') }}</div>
                    <div class="signature-title">Date of Conferment</div>
                </div>
                
                <div class="cert-seal-wrapper">
                    <div class="cert-seal">
                        <i class="bi bi-patch-check-fill"></i>
                        <span class="cert-seal-text">Certified</span>
                    </div>
                </div>
                
                <div class="signature-block">
                    <div class="signature-line"></div>
                    <div class="signature-name">Educater Academy</div>
                    <div class="signature-title">Authorized Institution</div>
                </div>
            </div>
            
            <div class="cert-number-box">
                <span>VERIFY: LMS.ACADEMY/VERIFY/{{ $certificate->certificate_number }}</span>
                <span>ID: {{ $certificate->certificate_number }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
