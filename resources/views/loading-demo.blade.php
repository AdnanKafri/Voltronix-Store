@extends('layouts.app')

@section('title', 'Loading System Demo - Voltronix')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Demo Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold mb-3" style="font-family: 'Orbitron', sans-serif;">
                    <span class="text-gradient">Loading System Demo</span>
                </h1>
                <p class="lead text-muted">
                    Experience the smooth loading and transition effects of the Voltronix Digital Store
                </p>
            </div>

            <!-- Demo Cards -->
            <div class="row g-4">
                <!-- Preloader Demo -->
                <div class="col-md-6" data-animate="fade-in">
                    <div class="card h-100 shadow-lg border-0" style="background: linear-gradient(145deg, #ffffff, #f8f9fa);">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-gradient rounded-circle p-3 me-3">
                                    <i class="bi bi-hourglass-split text-white fs-4"></i>
                                </div>
                                <h5 class="card-title mb-0 fw-bold">Initial Page Load</h5>
                            </div>
                            <p class="card-text text-muted mb-4">
                                The preloader appears when the site first loads, featuring the Voltronix logo with smooth animations.
                            </p>
                            <button class="btn btn-primary" onclick="simulatePageLoad()">
                                <i class="bi bi-arrow-clockwise me-2"></i>Simulate Page Load
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Navigation Demo -->
                <div class="col-md-6" data-animate="fade-in">
                    <div class="card h-100 shadow-lg border-0" style="background: linear-gradient(145deg, #ffffff, #f8f9fa);">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-gradient rounded-circle p-3 me-3">
                                    <i class="bi bi-arrow-right-circle text-white fs-4"></i>
                                </div>
                                <h5 class="card-title mb-0 fw-bold">Page Transitions</h5>
                            </div>
                            <p class="card-text text-muted mb-4">
                                Smooth transitions between pages with progress bar and overlay effects.
                            </p>
                            <button class="btn btn-success" onclick="simulateNavigation()">
                                <i class="bi bi-box-arrow-right me-2"></i>Simulate Navigation
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar Demo -->
                <div class="col-md-6" data-animate="fade-in">
                    <div class="card h-100 shadow-lg border-0" style="background: linear-gradient(145deg, #ffffff, #f8f9fa);">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-gradient rounded-circle p-3 me-3">
                                    <i class="bi bi-speedometer2 text-white fs-4"></i>
                                </div>
                                <h5 class="card-title mb-0 fw-bold">Progress Indicator</h5>
                            </div>
                            <p class="card-text text-muted mb-4">
                                Top progress bar provides visual feedback during navigation and loading states.
                            </p>
                            <button class="btn btn-warning" onclick="simulateProgress()">
                                <i class="bi bi-bar-chart me-2"></i>Show Progress Bar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Loading States Demo -->
                <div class="col-md-6" data-animate="fade-in">
                    <div class="card h-100 shadow-lg border-0" style="background: linear-gradient(145deg, #ffffff, #f8f9fa);">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info bg-gradient rounded-circle p-3 me-3">
                                    <i class="bi bi-gear-fill text-white fs-4"></i>
                                </div>
                                <h5 class="card-title mb-0 fw-bold">Element Loading</h5>
                            </div>
                            <p class="card-text text-muted mb-4">
                                Individual elements can show loading states with professional spinners and overlays.
                            </p>
                            <button class="btn btn-info" id="loadingBtn" onclick="simulateElementLoading(this)">
                                <i class="bi bi-download me-2"></i>Simulate Loading
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features List -->
            <div class="mt-5" data-animate="fade-in">
                <div class="card shadow-lg border-0" style="background: linear-gradient(145deg, #ffffff, #f8f9fa);">
                    <div class="card-body p-5">
                        <h3 class="card-title fw-bold mb-4 text-center">
                            <span class="text-gradient">Loading System Features</span>
                        </h3>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <strong>Smooth Preloader:</strong> Brand-consistent initial loading animation
                                    </li>
                                    <li class="mb-3">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <strong>Page Transitions:</strong> Seamless navigation between pages
                                    </li>
                                    <li class="mb-3">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <strong>Progress Indicators:</strong> Visual feedback for all operations
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <strong>Responsive Design:</strong> Optimized for all screen sizes
                                    </li>
                                    <li class="mb-3">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <strong>Bilingual Support:</strong> RTL/LTR compatible animations
                                    </li>
                                    <li class="mb-3">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <strong>Performance Optimized:</strong> GPU-accelerated animations
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.text-gradient {
    background: linear-gradient(135deg, #007fff, #23efff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 127, 255, 0.1) !important;
}

[data-animate] {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

[data-animate].fade-in {
    opacity: 1;
    transform: translateY(0);
}
</style>

<script>
// Demo functions
function simulatePageLoad() {
    // Show preloader
    const preloader = document.createElement('div');
    preloader.className = 'preloader';
    preloader.innerHTML = `
        <div class="preloader-content">
            <div class="preloader-logo">
                <img src="{{ asset('images/logo nt.png') }}" alt="Voltronix" onerror="this.style.display='none'">
            </div>
            <div class="preloader-text">{{ __('app.brand.name') }}</div>
            <div class="preloader-spinner">
                <div class="spinner-ring"></div>
            </div>
        </div>
    `;
    document.body.appendChild(preloader);
    
    // Hide after 3 seconds
    setTimeout(() => {
        preloader.classList.add('fade-out');
        setTimeout(() => preloader.remove(), 500);
    }, 3000);
}

function simulateNavigation() {
    if (window.voltronixLoader) {
        window.voltronixLoader.showProgressBar();
        window.voltronixLoader.showPageTransition();
        
        setTimeout(() => {
            window.voltronixLoader.completeProgressBar();
            window.voltronixLoader.hidePageTransition();
        }, 2000);
    }
}

function simulateProgress() {
    if (window.voltronixLoader) {
        window.voltronixLoader.showProgressBar();
        setTimeout(() => {
            window.voltronixLoader.completeProgressBar();
        }, 3000);
    }
}

function simulateElementLoading(button) {
    if (window.voltronixLoader) {
        window.voltronixLoader.showElementLoading(button, 'Loading...');
        
        setTimeout(() => {
            window.voltronixLoader.hideElementLoading(button);
        }, 3000);
    }
}

// Animate elements on scroll
document.addEventListener('DOMContentLoaded', function() {
    const animatedElements = document.querySelectorAll('[data-animate]');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('fade-in');
                }, index * 100);
            }
        });
    });
    
    animatedElements.forEach(element => {
        observer.observe(element);
    });
});
</script>
@endsection
