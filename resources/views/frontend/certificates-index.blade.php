@extends('frontend.layouts.app')

@section('title', 'My Certificates')

@push('styles')
<style>
    .certificates-page {
        background: #06090f;
        min-height: 90vh;
        padding: 50px 0;
        color: #c9d1d9;
    }

    .cert-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }

    .cert-card {
        background: #0d1117;
        border: 1px solid #30363d;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .cert-card:hover {
        transform: translateY(-10px);
        border-color: #10b981;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 20px rgba(16, 185, 129, 0.1);
    }

    .cert-preview {
        aspect-ratio: 1.414 / 1;
        background: #161b22;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
        position: relative;
        border-bottom: 1px solid #30363d;
        overflow: hidden;
    }

    .mini-cert-content {
        text-align: center;
        z-index: 2;
    }

    .mini-cert-label {
        font-size: 0.6rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #10b981;
        margin-bottom: 8px;
        font-weight: 700;
        opacity: 0.8;
    }

    .mini-cert-title {
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 5px;
    }

    .mini-cert-user {
        font-size: 0.7rem;
        color: #8b949e;
    }

    .cert-badge-icon {
        position: absolute;
        font-size: 5rem;
        color: rgba(16, 185, 129, 0.05);
        z-index: 1;
    }

    .cert-overlay {
        position: absolute;
        inset: 0;
        background: rgba(16, 185, 129, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
        z-index: 3;
    }

    .cert-card:hover .cert-overlay {
        opacity: 1;
    }

    .view-btn {
        background: #10b981;
        color: #000;
        padding: 10px 24px;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        transform: translateY(20px);
        transition: all 0.3s;
    }

    .cert-card:hover .view-btn {
        transform: translateY(0);
    }

    .cert-info {
        padding: 20px;
    }

    .cert-name-label {
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 10px;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cert-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: #8b949e;
    }

    .id-slug {
        font-family: monospace;
        color: #10b981;
        background: rgba(16, 185, 129, 0.1);
        padding: 2px 8px;
        border-radius: 4px;
    }

    .empty-state {
        text-align: center;
        padding: 100px 20px;
        background: #0d1117;
        border-radius: 24px;
        border: 1px dashed #30363d;
        margin-top: 50px;
    }

    .empty-icon {
        font-size: 4rem;
        color: #30363d;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="certificates-page">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fw-bold text-white mb-2">My Certificates</h1>
                <p class="text-muted">You've earned {{ $certificates->count() }} certificates so far.</p>
            </div>
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-light rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i> Dashboard
            </a>
        </div>

        @if($certificates->count() > 0)
            <div class="cert-grid">
                @foreach($certificates as $certificate)
                    <div class="cert-card">
                        <div class="cert-preview">
                            <i class="bi bi-award cert-badge-icon"></i>
                            <div class="mini-cert-content">
                                <div class="mini-cert-label">Certificate of Achievement</div>
                                <div class="mini-cert-title">{{ $certificate->course->title }}</div>
                                <div class="mini-cert-user">Awarded to {{ Auth::user()->name }}</div>
                            </div>
                            <div class="cert-overlay">
                                <a href="{{ route('student.certificates.show', $certificate->id) }}" class="view-btn">View Full Certificate</a>
                            </div>
                        </div>
                        <div class="cert-info">
                            <span class="cert-name-label" title="{{ $certificate->course->title }}">
                                {{ $certificate->course->title }}
                            </span>
                            <div class="cert-meta">
                                <span><i class="bi bi-calendar3 me-1"></i> {{ $certificate->issued_at->format('M d, Y') }}</span>
                                <span class="id-slug">{{ $certificate->certificate_number }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-award empty-icon"></i>
                <h2 class="text-white fw-bold">No Certificates Yet</h2>
                <p class="text-muted mb-4">Complete your courses and quizzes to earn professional certificates.</p>
                <a href="{{ route('frontend.home') }}#courses" class="btn btn-success rounded-pill px-5 py-2 fw-bold">Browse Courses</a>
            </div>
        @endif
    </div>
</div>
@endsection
