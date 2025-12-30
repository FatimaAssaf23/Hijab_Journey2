@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-5 fw-bold text-primary">Lessons</h1>
    <div class="row g-4 justify-content-center">
        <!-- Sample Lesson Cards -->
        <div class="col-12 col-sm-6 col-md-3 mb-4">
            <div class="card lesson-card unlocked">
                <div class="card-img-overlay d-flex flex-column justify-content-end" style="background: linear-gradient(135deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.8) 100%);">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-2 text-white">Introduction to Hijab</h5>
                        <p class="card-text text-light opacity-75 mb-0">
                            <i class="fas fa-clock me-2"></i>30 minutes
                        </p>
                    </div>
                </div>
                <div class="card-overlay" style="background-image: url('/images/lesson1.jpg');"></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3 mb-4">
            <div class="card lesson-card locked">
                <div class="card-img-overlay d-flex flex-column justify-content-end" style="background: linear-gradient(135deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.8) 100%);">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-2 text-white">Hijab Styles</h5>
                        <p class="card-text text-light opacity-75 mb-0">
                            <i class="fas fa-clock me-2"></i>45 minutes
                        </p>
                    </div>
                </div>
                <div class="card-overlay" style="background-image: url('/images/lesson2.jpg');"></div>
                <div class="lock-overlay">
                    <div class="lock-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3 mb-4">
            <div class="card lesson-card locked">
                <div class="card-img-overlay d-flex flex-column justify-content-end" style="background: linear-gradient(135deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.8) 100%);">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-2 text-white">Modest Fashion</h5>
                        <p class="card-text text-light opacity-75 mb-0">
                            <i class="fas fa-clock me-2"></i>60 minutes
                        </p>
                    </div>
                </div>
                <div class="card-overlay" style="background-image: url('/images/lesson3.jpg');"></div>
                <div class="lock-overlay">
                    <div class="lock-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add more cards as needed -->
    </div>
</div>

<style>
.lesson-card {
    height: 280px;
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1), 0 1px 8px rgba(0,0,0,0.1);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    background: #fff;
    margin: 0 auto;
    max-width: 100%;
}

.lesson-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 50%);
    pointer-events: none;
    z-index: 1;
}

.lesson-card.unlocked:hover {
    transform: translateY(-12px) scale(1.02);
    box-shadow: 0 20px 60px rgba(0,0,0,0.15), 0 8px 20px rgba(0,0,0,0.1);
    cursor: pointer;
            @foreach($lessons as $lesson)
            <div class="col-12 col-sm-6 col-md-3 mb-4">
                <div class="card lesson-card {{ $lesson->is_visible ? 'unlocked' : 'locked' }}">
                    <div class="card-img-overlay d-flex flex-column justify-content-end" style="background: linear-gradient(135deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.8) 100%);">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-2 text-white">{{ $lesson->title }}</h5>
                            <p class="card-text text-light opacity-75 mb-0">
                                <i class="fas fa-clock me-2"></i>{{ $lesson->duration_minutes ?? 'N/A' }} minutes
                            </p>
                        </div>
                    </div>
                    <div class="card-overlay" style="background-image: url('{{ $lesson->content_url ?? '/images/default_lesson.jpg' }}');"></div>
                    @if(!$lesson->is_visible)
                    <div class="lock-overlay">
                        <div class="lock-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                    @endif
                </div>
    bottom: 0;
            @endforeach
    background-size: cover;
    background-position: center;
    transition: transform 0.4s ease;
    z-index: 0;
}

.card-img-overlay {
    z-index: 2;
    position: relative;
}

.card-body {
    background: none !important;
    border-radius: 0 !important;
    padding: 1.5rem !important;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 700 !important;
    text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    margin-bottom: 0.5rem !important;
}

.card-text {
    font-size: 0.9rem;
    font-weight: 500;
    text-shadow: 0 1px 2px rgba(0,0,0,0.5);
}

.lock-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 3;
}

.lock-icon {
    background: rgba(0,0,0,0.7);
    color: #ffffff;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.3s ease;
}

.lock-icon:hover {
    background: rgba(0,0,0,0.8);
    transform: scale(1.1);
}

.fas.fa-clock {
    color: rgba(255,255,255,0.8);
}

/* Ensure proper grid behavior */
.row {
    display: flex;
    flex-wrap: wrap;
}

.col-12, .col-sm-6, .col-md-3 {
    padding-left: 15px;
    padding-right: 15px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .lesson-card {
        height: 250px;
        margin-bottom: 2rem;
    }

    .card-title {
        font-size: 1.1rem;
    }
}

@media (min-width: 768px) {
    .col-md-3 {
        flex: 0 0 25%;
        max-width: 25%;
    }
}
</style>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
@endsection