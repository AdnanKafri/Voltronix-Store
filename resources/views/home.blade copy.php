@extends('layouts.app')

@section('title', __('app.hero.title') . ' - ' . __('app.hero.subtitle'))
@section('description', __('app.footer.description'))

@push('styles')
<style>
    /* MODERN HERO SLIDER DESIGN */
    
    .hero-slider {
        height: 100vh;
        min-height: 600px;
        position: relative;
        overflow: hidden;
        margin-top: 0;
        box-sizing: border-box;
        padding-top: var(--navbar-height-desktop);
    }
    
    @media (max-width: 768px) {
        .hero-slider {
            height: calc(100vh - var(--navbar-height-mobile));
            min-height: 500px;
            padding-top: var(--navbar-height-mobile);
        }
    }
    
    @media (max-width: 576px) {
        .hero-slider {
            height: calc(100vh - var(--navbar-height-mobile));
            min-height: 450px;
            padding-top: calc(var(--navbar-height-mobile) + 1rem);
        }
    }
    
    .slider-container {
        position: relative;
        height: 100%;
        width: 100%;
    }
    
    .slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 1s ease-in-out;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .slide.active {
        opacity: 1;
    }
    
    .slide-1 {
        background: linear-gradient(135deg, var(--voltronix-dark) 0%, #1a1a2e 50%, var(--voltronix-dark) 100%);
    }
    
    .slide-2 {
        background: linear-gradient(135deg, #0d1421 0%, #1e3a8a 50%, #0d1421 100%);
    }
    
    .slide-3 {
        background: linear-gradient(135deg, #1a1a2e 0%, #0f172a 50%, #1a1a2e 100%);
    }
    
    .slide::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 30% 40%, rgba(0, 127, 255, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 70% 60%, rgba(35, 239, 255, 0.2) 0%, transparent 50%);
        animation: slideAnimation 8s ease-in-out infinite alternate;
    }
    
    }
    
    /* Slide Content Positioning */
    .slide-content {
        text-align: center;
        color: white;
        z-index: 3;
        position: relative;
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
    }
    
    .slide-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 3.5rem;
        font-weight: 900;
        margin-bottom: 1.5rem;
        text-shadow: 0 4px 20px rgba(0, 127, 255, 0.5);
        letter-spacing: 2px;
        line-height: 1.1;
        text-align: center;
        margin-left: auto;
        margin-right: auto;
        width: 100%;
    }
    
    .slide-subtitle {
        font-size: 1.3rem;
        margin-bottom: 2.5rem;
        opacity: 0.9;
        line-height: 1.6;
        text-align: center;
        margin-left: auto;
        margin-right: auto;
        max-width: 600px;
        width: 100%;
    }
    
    .slide-buttons {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    /* Slider Controls */
    .slider-controls {
        position: absolute;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 1rem;
        z-index: 20;
    }
    
    .slider-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .slider-dot.active {
        background: var(--voltronix-primary);
        border-color: white;
        transform: scale(1.2);
    }
    
    .slider-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        z-index: 20;
        font-size: 1.2rem;
    }
    
    .slider-nav:hover {
        background: var(--voltronix-primary);
        border-color: var(--voltronix-secondary);
        transform: translateY(-50%) scale(1.1);
    }
    
    .slider-prev {
        left: 2rem;
    }
    
    .slider-next {
        right: 2rem;
    }
    
    .mega-subtitle {
        font-size: 1.8rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 3rem;
        animation: fadeInUp 1s ease-out 0.2s both;
        font-weight: 300;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .slide-buttons {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 2rem;
    }
    
    /* COMPREHENSIVE MOBILE RESPONSIVENESS */
    
    /* Tablet Landscape (992px - 1199px) */
    @media (max-width: 1199px) and (min-width: 992px) {
        .slide-content {
            padding: 1.5rem;
            max-width: 700px;
        }
        
        .slide-title {
            font-size: 3rem;
        }
        
        .slide-subtitle {
            font-size: 1.3rem;
        }
    }
    
    /* Tablet portrait (768px - 991px) */
    @media (max-width: 991px) and (min-width: 768px) {
        .slide-content {
            padding: 1.5rem;
            max-width: 90%;
        }
        
        .slide-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            letter-spacing: 1px;
        }
        
        .slide-subtitle {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
        }
        
        .slide-buttons {
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .slide-buttons .btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
        }
    }
    
    /* Mobile Large (576px - 767px) */
    @media (max-width: 767px) and (min-width: 576px) {
        .slide-content {
            padding: 1rem;
            max-width: 95%;
        }
        
        .slide-title {
            font-size: 2rem;
            margin-bottom: 1rem;
            letter-spacing: 1px;
            line-height: 1.2;
        }
        
        .slide-subtitle {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            line-height: 1.4;
        }
        
        .slide-buttons {
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1.5rem;
            width: 100%;
        }
        
        .slide-buttons .btn {
            width: 100%;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            border-radius: 12px;
        }
    }
    
    /* Mobile Small (320px - 575px) */
    @media (max-width: 575px) {
        .slide-content {
            padding: 0.75rem;
            max-width: 95%;
        }
        
        .slide-title {
            font-size: 1.75rem;
            margin-bottom: 0.75rem;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }
        
        .slide-subtitle {
            font-size: 1rem;
            margin-bottom: 1.25rem;
            line-height: 1.4;
        }
        
        .slide-buttons {
            flex-direction: column;
            gap: 0.5rem;
            margin-top: 1.25rem;
            width: 100%;
        }
        
        .slide-buttons .btn {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            border-radius: 10px;
        }
    }
    
    /* Ultra Small Mobile (below 320px) */
    @media (max-width: 319px) {
        .slide-content {
            padding: 0.5rem;
            max-width: 98%;
        }
        
        .slide-title {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .slide-subtitle {
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .slide-buttons .btn {
            padding: 0.6rem 0.8rem;
            font-size: 0.9rem;
        }
    }
    
    .scroll-indicator {
        position: absolute;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        color: rgba(255, 255, 255, 0.7);
        animation: bounce 2s infinite;
        font-size: 2rem;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateX(-50%) translateY(0); }
        40% { transform: translateX(-50%) translateY(-10px); }
        60% { transform: translateX(-50%) translateY(-5px); }
    }
    
    /* Revolutionary Product Showcase */
    .product-showcase {
        background: var(--voltronix-gradient-light);
        padding: 8rem 0;
        position: relative;
        overflow: hidden;
    }
    
    .product-showcase::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            linear-gradient(45deg, transparent 30%, rgba(0, 127, 255, 0.05) 50%, transparent 70%),
            linear-gradient(-45deg, transparent 30%, rgba(35, 239, 255, 0.05) 50%, transparent 70%);
        animation: showcaseAnimation 6s ease-in-out infinite alternate;
    }
    
    @keyframes showcaseAnimation {
        0% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    .showcase-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 4rem;
        font-weight: 900;
        text-align: center;
        margin-bottom: 1rem;
        background: var(--voltronix-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
        z-index: 2;
    }
    
    .showcase-subtitle {
        text-align: center;
        font-size: 1.3rem;
        color: #666;
        margin-bottom: 5rem;
        position: relative;
        z-index: 2;
    }
    
    /* ========================================
       VOLTRONIX CATEGORIES SECTION
       REFINED PREMIUM DESIGN
       ======================================== */
    
    /* Categories Section Container */
    .categories-section {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 50%, #ffffff 100%);
        padding: 6rem 0;
        position: relative;
        overflow: hidden;
    }
    
    .categories-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 15% 25%, rgba(0, 127, 255, 0.04) 0%, transparent 50%),
            radial-gradient(circle at 85% 75%, rgba(35, 239, 255, 0.04) 0%, transparent 50%);
        animation: categoriesFloat 10s ease-in-out infinite alternate;
        pointer-events: none;
    }
    
    @keyframes categoriesFloat {
        0% { opacity: 0.6; transform: scale(1) rotate(0deg); }
        100% { opacity: 1; transform: scale(1.03) rotate(1deg); }
    }
    
    /* Categories Grid */
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2.25rem;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    
    /* Category Card */
    .category-card-modern {
        background: #ffffff;
        border-radius: 24px;
        overflow: hidden;
        position: relative;
        transition: all 0.45s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0, 127, 255, 0.06);
        box-shadow: 
            0 2px 12px rgba(0, 0, 0, 0.04),
            0 1px 3px rgba(0, 0, 0, 0.02);
        height: 100%;
        display: flex;
        flex-direction: column;
        backdrop-filter: blur(10px);
    }
    
    .category-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--voltronix-gradient);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 10;
    }
    
    .category-card-modern:hover {
        transform: translateY(-10px);
        border-color: rgba(0, 127, 255, 0.15);
        box-shadow: 
            0 16px 48px rgba(0, 127, 255, 0.12),
            0 8px 24px rgba(0, 0, 0, 0.06);
    }
    
    .category-card-modern:hover::before {
        transform: scaleX(1);
    }
    
    /* Category Image Container */
    .category-image-wrapper {
        position: relative;
        width: 100%;
        height: 220px;
        overflow: hidden;
        background: linear-gradient(135deg, 
            rgba(0, 127, 255, 0.05) 0%, 
            rgba(35, 239, 255, 0.05) 100%);
    }
    
    .category-image-wrapper::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
            180deg,
            transparent 0%,
            rgba(0, 0, 0, 0.15) 70%,
            rgba(0, 0, 0, 0.35) 100%
        );
        z-index: 2;
        transition: opacity 0.45s ease;
    }
    
    .category-card-modern:hover .category-image-wrapper::after {
        opacity: 0.7;
    }
    
    .category-card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: all 0.45s cubic-bezier(0.4, 0, 0.2, 1);
        filter: brightness(1) contrast(1.05) saturate(1.1);
    }
    
    .category-card-modern:hover .category-card-image {
        transform: scale(1.08);
        filter: brightness(1.1) contrast(1.1) saturate(1.15);
    }
    
    /* Category Icon (for cards without images) */
    .category-icon-wrapper {
        width: 100%;
        height: 220px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, 
            rgba(0, 127, 255, 0.08) 0%, 
            rgba(35, 239, 255, 0.08) 100%);
        position: relative;
        overflow: hidden;
    }
    
    .category-icon-wrapper::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            45deg,
            transparent 30%,
            rgba(0, 127, 255, 0.08) 50%,
            transparent 70%
        );
        animation: iconShine 4s ease-in-out infinite;
    }
    
    @keyframes iconShine {
        0%, 100% { transform: translate(-25%, -25%) rotate(0deg); }
        50% { transform: translate(25%, 25%) rotate(180deg); }
    }
    
    .category-icon-large {
        font-size: 4.5rem;
        color: var(--voltronix-primary);
        opacity: 0.85;
        filter: drop-shadow(0 4px 16px rgba(0, 127, 255, 0.25));
        z-index: 2;
        position: relative;
        transition: all 0.45s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .category-card-modern:hover .category-icon-large {
        transform: scale(1.12) rotate(5deg);
        opacity: 1;
        filter: drop-shadow(0 6px 20px rgba(0, 127, 255, 0.35));
    }
    
    /* Category Content */
    .category-card-content {
        padding: 2rem 1.75rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        position: relative;
        z-index: 3;
        background: #ffffff;
    }
    
    .category-card-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--voltronix-accent);
        margin-bottom: 0.85rem;
        line-height: 1.35;
        transition: all 0.35s ease;
        letter-spacing: -0.02em;
    }
    
    .category-card-modern:hover .category-card-title {
        color: var(--voltronix-primary);
        transform: translateX(2px);
    }
    
    .category-card-description {
        color: #6c757d;
        font-size: 0.95rem;
        line-height: 1.65;
        margin-bottom: 1.5rem;
        flex: 1;
        font-weight: 400;
    }
    
    /* Category Meta */
    .category-card-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 1.25rem;
        border-top: 1px solid rgba(0, 127, 255, 0.08);
        margin-top: auto;
    }
    
    .category-product-count {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, 
            rgba(0, 127, 255, 0.06) 0%, 
            rgba(35, 239, 255, 0.06) 100%);
        padding: 0.55rem 1.1rem;
        border-radius: 14px;
        font-family: 'Orbitron', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--voltronix-primary);
        border: 1px solid rgba(0, 127, 255, 0.12);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        letter-spacing: -0.01em;
    }
    
    .category-product-count i {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .category-card-modern:hover .category-product-count {
        background: linear-gradient(135deg, 
            rgba(0, 127, 255, 0.12) 0%, 
            rgba(35, 239, 255, 0.12) 100%);
        border-color: rgba(0, 127, 255, 0.25);
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 127, 255, 0.15);
    }
    
    .category-card-arrow {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, 
            var(--voltronix-primary) 0%, 
            var(--voltronix-secondary) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 14px rgba(0, 127, 255, 0.22);
    }
    
    .category-card-modern:hover .category-card-arrow {
        transform: translateX(5px) scale(1.08);
        box-shadow: 0 6px 20px rgba(0, 127, 255, 0.35);
    }
    
    /* ========================================
       RESPONSIVE DESIGN
       ======================================== */
    
    /* Large Desktop */
    @media (min-width: 1400px) {
        .categories-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 2.5rem;
            padding: 0 2rem;
        }
        
        .category-image-wrapper,
        .category-icon-wrapper {
            height: 240px;
        }
    }
    
    /* Desktop */
    @media (min-width: 1200px) and (max-width: 1399px) {
        .categories-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }
        
        .category-image-wrapper,
        .category-icon-wrapper {
            height: 220px;
        }
    }
    
    /* Tablet */
    @media (min-width: 768px) and (max-width: 1199px) {
        .categories-section {
            padding: 5rem 0;
        }
        
        .categories-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.75rem;
            padding: 0 1.25rem;
        }
        
        .category-image-wrapper,
        .category-icon-wrapper {
            height: 200px;
        }
        
        .category-card-content {
            padding: 1.75rem 1.5rem;
        }
        
        .category-card-title {
            font-size: 1.25rem;
        }
        
        .category-icon-large {
            font-size: 4rem;
        }
    }
    
    /* Mobile */
    @media (max-width: 767px) {
        .categories-section {
            padding: 4rem 0;
        }
        
        .categories-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            padding: 0 1rem;
        }
        
        .category-image-wrapper,
        .category-icon-wrapper {
            height: 180px;
        }
        
        .category-card-content {
            padding: 1.5rem 1.25rem;
        }
        
        .category-card-title {
            font-size: 1.2rem;
        }
        
        .category-card-description {
            font-size: 0.9rem;
            line-height: 1.6;
        }
        
        .category-icon-large {
            font-size: 3.5rem;
        }
        
        .category-product-count {
            font-size: 0.8rem;
            padding: 0.45rem 0.9rem;
        }
        
        .category-card-arrow {
            width: 36px;
            height: 36px;
        }
    }
    
    /* ========================================
       PERFORMANCE OPTIMIZATIONS
       ======================================== */
    
    .category-card-modern,
    .category-card-image,
    .category-icon-large,
    .category-card-arrow,
    .category-product-count {
        will-change: transform;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }
    
    .category-card-title,
    .category-product-count {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
    }
    
    
    
    
    
    
    
    
    
    
    /* BRAND-ALIGNED PROMOTIONAL BANNER */
    .mega-promo-banner {
        background: linear-gradient(135deg, var(--voltronix-primary) 0%, var(--voltronix-secondary) 100%);
        padding: 6rem 0;
        position: relative;
        overflow: hidden;
        color: white;
    }
    
    .mega-promo-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 10% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 90% 80%, rgba(0, 127, 255, 0.2) 0%, transparent 50%);
        animation: promoAnimation 6s ease-in-out infinite alternate;
    }
    
    @keyframes promoAnimation {
        0% { opacity: 0.7; transform: rotate(0deg); }
        100% { opacity: 1; transform: rotate(1deg); }
    }
    
    .promo-content {
        position: relative;
        z-index: 2;
    }
    
    .promo-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 3.5rem;
        font-weight: 900;
        margin-bottom: 1.5rem;
        text-shadow: 0 4px 20px rgba(0, 127, 255, 0.5);
    }
    
    .promo-subtitle {
        font-size: 1.3rem;
        margin-bottom: 3rem;
        opacity: 0.9;
        line-height: 1.6;
    }
    
    .promo-features {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        margin-bottom: 3rem;
    }
    
    .promo-feature {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 1.1rem;
        font-weight: 500;
    }
    
    .promo-feature i {
        font-size: 1.5rem;
        color: var(--voltronix-secondary);
    }
    
    .promo-visual {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .promo-circle {
        width: 250px;
        height: 250px;
        border-radius: 50%;
        background: var(--voltronix-gradient);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        animation: promoCircle 3s ease-in-out infinite;
        box-shadow: var(--shadow-xl);
        position: relative;
    }
    
    .promo-circle::before {
        content: '';
        position: absolute;
        top: -10px;
        left: -10px;
        right: -10px;
        bottom: -10px;
        border-radius: 50%;
        background: var(--voltronix-gradient-reverse);
        z-index: -1;
        animation: promoCircleOuter 3s ease-in-out infinite reverse;
    }
    
    @keyframes promoCircle {
        0%, 100% { transform: scale(1) rotate(0deg); }
        50% { transform: scale(1.05) rotate(180deg); }
    }
    
    @keyframes promoCircleOuter {
        0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.7; }
        50% { transform: scale(1.1) rotate(-180deg); opacity: 1; }
    }
    
    .promo-percentage {
        font-family: 'Orbitron', sans-serif;
        font-size: 4rem;
        font-weight: 900;
        color: white;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }
    
    .promo-off {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        margin-top: -0.5rem;
    }
    
    /* ULTRA MODERN FEATURES */
    .ultra-features {
        background: var(--voltronix-gradient-light);
        padding: 8rem 0;
        position: relative;
    }
    
    .features-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 4rem;
        font-weight: 900;
        text-align: center;
        margin-bottom: 1rem;
        background: var(--voltronix-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .features-subtitle {
        text-align: center;
        font-size: 1.3rem;
        color: #666;
        margin-bottom: 5rem;
    }
    
    /* Ultra Modern Feature Cards */
    .ultra-feature-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: var(--border-radius-xl);
        padding: 3rem 2rem;
        text-align: center;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(20px);
        box-shadow: var(--shadow-lg);
        height: 100%;
    }
    
    .ultra-feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: var(--voltronix-gradient);
        transform: scaleX(0);
        transition: transform 0.5s ease;
    }
    
    .ultra-feature-card:hover {
        transform: translateY(-15px) scale(1.02);
        box-shadow: var(--shadow-xl);
        border-color: var(--voltronix-primary);
    }
    
    .ultra-feature-card:hover::before {
        transform: scaleX(1);
    }
    
    .feature-icon-ultra {
        width: 100px;
        height: 100px;
        margin: 0 auto 2rem;
        background: var(--voltronix-gradient);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: white;
        transition: all 0.5s ease;
        box-shadow: var(--shadow-md);
    }
    
    .ultra-feature-card:hover .feature-icon-ultra {
        transform: rotate(360deg) scale(1.1);
        box-shadow: var(--shadow-lg);
    }
    
    .feature-title-ultra {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--voltronix-accent);
        margin-bottom: 1rem;
    }
    
    .feature-desc-ultra {
        color: #666;
        line-height: 1.6;
        margin-bottom: 2rem;
        font-size: 1rem;
    }
    
    .feature-highlight {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        color: var(--voltronix-primary);
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .feature-highlight i {
        font-size: 1rem;
    }
    
    .hero-buttons {
        animation: fadeInUp 1s ease-out 0.4s both;
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .hero-main-logo {
        max-width: 350px;
        width: 100%;
        height: auto;
        filter: drop-shadow(0 15px 35px rgba(0, 127, 255, 0.4)) brightness(1.1);
        animation: fadeInUp 1s ease-out 0.1s both, float 6s ease-in-out infinite;
        margin-bottom: 2rem;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .section-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 2.8rem;
        font-weight: 700;
        color: var(--voltronix-accent);
        margin-bottom: 3.5rem;
        position: relative;
        text-align: center;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: var(--voltronix-gradient);
        border-radius: 2px;
    }
    
    .section-subtitle {
        font-size: 1.2rem;
        color: #6c757d;
        text-align: center;
        margin-bottom: 4rem;
        font-weight: 300;
        line-height: 1.6;
    }
    
    /* Enhanced Category Cards */
    .category-card {
        background: linear-gradient(145deg, #ffffff, #f8f9fa);
        border-radius: 25px;
        padding: 2.5rem 2rem;
        text-align: center;
        height: 100%;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }
    
    .category-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--voltronix-gradient);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }
    
    .category-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 25px 50px rgba(0, 127, 255, 0.15);
        border-color: var(--voltronix-primary);
    }
    
    .category-card:hover::before {
        transform: scaleX(1);
    }
    
    .category-icon {
        width: 90px;
        height: 90px;
        background: var(--voltronix-gradient);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        font-size: 2.2rem;
        color: white;
        box-shadow: 0 8px 25px rgba(0, 127, 255, 0.3);
        transition: all 0.3s ease;
    }
    
    .category-card:hover .category-icon {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 12px 35px rgba(0, 127, 255, 0.4);
    }
    
    .category-card h4 {
        font-family: 'Orbitron', sans-serif;
        font-weight: 600;
        color: var(--voltronix-accent);
        margin-bottom: 1rem;
        font-size: 1.3rem;
    }
    
    .category-card p {
        color: #6c757d;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }
    
    /* Enhanced Feature Cards */
    .feature-card {
        text-align: center;
        padding: 3rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
    }
    
    .feature-icon {
        width: 110px;
        height: 110px;
        background: var(--voltronix-gradient);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        font-size: 2.8rem;
        color: white;
        box-shadow: 0 15px 35px rgba(0, 127, 255, 0.3);
        transition: all 0.3s ease;
    }
    
    .feature-card:hover .feature-icon {
        transform: scale(1.05);
        box-shadow: 0 20px 45px rgba(0, 127, 255, 0.4);
    }
    
    .feature-card h4 {
        font-family: 'Orbitron', sans-serif;
        font-weight: 600;
        color: var(--voltronix-accent);
        margin-bottom: 1rem;
        font-size: 1.3rem;
    }
    
    /* Enhanced Stats Section */
    .stats-section {
        background: linear-gradient(135deg, var(--voltronix-accent), #000000);
        color: white;
        padding: 6rem 0;
        position: relative;
        overflow: hidden;
    }
    
    .stats-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 50%, rgba(0, 127, 255, 0.1) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite alternate;
    }
    
    .stat-card {
        text-align: center;
        padding: 2rem 1rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-5px);
    }
    
    .stat-number {
        font-family: 'Orbitron', sans-serif;
        font-size: 3.5rem;
        font-weight: 900;
        background: var(--voltronix-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: block;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
    }
    
    /* Special Offers Section - Modern Design */
    .special-offers-section {
        background: var(--voltronix-light);
        padding: 5rem 0;
        position: relative;
    }
    
    /* Ensure all main sections have consistent background */
    .ultra-categories,
    .latest-products-section,
    .trending-products-section,
    .special-offers-section,
    .ultra-features,
    .stats-section,
    .about-section,
    .contact-section {
        background: var(--voltronix-light);
    }
    
    /* Alternate sections for visual variety */
    .trending-products-section,
    .ultra-features,
    .about-section {
        background: white;
    }
    
    .offer-card {
        background: white;
        border-radius: var(--border-radius-lg);
        padding: 0;
        height: 100%;
        min-height: 420px;
        box-shadow: var(--shadow-md);
        border: 2px solid rgba(0, 127, 255, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    .offer-image {
        height: 200px;
        overflow: hidden;
        position: relative;
        flex-shrink: 0;
    }
    
    .offer-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    
    .offer-card:hover .offer-image img {
        transform: scale(1.05);
    }
    
    .offer-content {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .offer-content h4 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: var(--voltronix-accent);
        line-height: 1.3;
        min-height: 2.6rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .offer-content p {
        color: #666;
        margin-bottom: 1rem;
        line-height: 1.5;
        flex: 1;
        min-height: 3rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .offer-pricing {
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    .offer-pricing .original-price {
        text-decoration: line-through;
        color: #999;
        font-size: 1rem;
    }
    
    .offer-pricing .discounted-price {
        color: var(--voltronix-primary);
        font-weight: 700;
        font-size: 1.25rem;
    }
    
    .offer-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--voltronix-gradient);
    }
    
    .offer-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
        border-color: var(--voltronix-primary);
    }
    
    .offer-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: var(--voltronix-gradient);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-md);
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        animation: pulse 2s ease-in-out infinite;
        z-index: 10;
        box-shadow: 0 4px 15px rgba(0, 127, 255, 0.3);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        text-align: center;
        min-width: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* RTL Support for Offer Badges */
    [dir="rtl"] .offer-badge {
        right: auto;
        left: 1rem;
    }
    
    /* Enhanced badge animations for offers */
    .offer-badge {
        animation: offerBadgePulse 2.5s ease-in-out infinite;
    }
    
    @keyframes offerBadgePulse {
        0%, 100% { 
            transform: scale(1); 
            box-shadow: 0 4px 15px rgba(0, 127, 255, 0.3);
        }
        50% { 
            transform: scale(1.05); 
            box-shadow: 0 6px 25px rgba(0, 127, 255, 0.5);
        }
    }
    
    /* Mobile Responsive for Offer Badges */
    @media (max-width: 768px) {
        .offer-badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            min-width: 70px;
        }
        
        .offer-content h4 {
            padding-right: 85px;
            font-size: 1.2rem;
        }
        
        [dir="rtl"] .offer-content h4 {
            padding-right: 0;
            padding-left: 85px;
        }
    }
    
    @media (max-width: 576px) {
        .offer-badge {
            font-size: 0.75rem;
            padding: 0.3rem 0.6rem;
            min-width: 60px;
        }
        
        .offer-content h4 {
            padding-right: 75px;
            font-size: 1.1rem;
        }
        
        [dir="rtl"] .offer-content h4 {
            padding-right: 0;
            padding-left: 75px;
        }
    }
    
    .offer-content {
        position: relative;
        z-index: 1;
    }
    
    .offer-content h4 {
        font-family: 'Orbitron', sans-serif;
        font-weight: 700;
        color: var(--voltronix-accent);
        margin-bottom: 1rem;
        font-size: 1.3rem;
        padding-right: 100px; /* Space for badge in LTR */
        line-height: 1.4;
    }
    
    /* RTL Support for Offer Content */
    [dir="rtl"] .offer-content h4 {
        padding-right: 0;
        padding-left: 100px; /* Space for badge in RTL */
    }
    
    .offer-content p {
        color: #666;
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }
    
    .offer-pricing {
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    /* RTL Support for Offer Pricing */
    [dir="rtl"] .offer-pricing {
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    
    .original-price {
        color: #999;
        text-decoration: line-through;
        font-size: 1.1rem;
    }
    
    .discounted-price {
        color: var(--voltronix-primary);
        font-size: 1.8rem;
        font-weight: 900;
        font-family: 'Orbitron', sans-serif;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    /* Latest Products Section - Enhanced */
    .latest-products-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        padding: 5rem 0;
        position: relative;
        overflow: hidden;
    }
    
    .latest-products-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 30%, rgba(0, 127, 255, 0.03) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(35, 239, 255, 0.03) 0%, transparent 50%);
        animation: backgroundFloat 8s ease-in-out infinite alternate;
    }
    
    @keyframes backgroundFloat {
        0% { opacity: 0.5; transform: scale(1); }
        100% { opacity: 1; transform: scale(1.02); }
    }
    
    .product-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        border: 2px solid rgba(0, 127, 255, 0.1);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
        backdrop-filter: blur(10px);
    }
    
    .product-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--voltronix-gradient);
        transform: scaleX(0);
        transition: transform 0.5s ease;
        z-index: 2;
    }
    
    .product-card:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 25px 60px rgba(0, 127, 255, 0.15);
        border-color: var(--voltronix-primary);
    }
    
    .product-card:hover::before {
        transform: scaleX(1);
    }
    
    .product-image {
        position: relative;
        height: 220px;
        overflow: hidden;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .product-card:hover .product-image img {
        transform: scale(1.08);
    }
    
    .product-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(0, 127, 255, 0.1), rgba(35, 239, 255, 0.05));
        color: var(--voltronix-primary);
        font-size: 3.5rem;
        transition: all 0.4s ease;
    }
    
    .product-card:hover .product-placeholder {
        transform: scale(1.1);
        color: var(--voltronix-secondary);
    }
    
    /* Enhanced Product Badges */
    .product-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 0.4rem 0.9rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        z-index: 3;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .product-badge.new {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        animation: badgeFloat 3s ease-in-out infinite;
    }
    
    .product-badge.sale {
        background: linear-gradient(135deg, #dc3545, #fd7e14);
        color: white;
        animation: badgePulse 2s ease-in-out infinite;
    }
    
    .product-badge.featured {
        background: linear-gradient(135deg, var(--voltronix-primary), var(--voltronix-secondary));
        color: white;
        animation: badgeGlow 2.5s ease-in-out infinite alternate;
    }
    
    /* RTL Badge Positioning */
    [dir="rtl"] .product-badge {
        right: auto;
        left: 12px;
    }
    
    @keyframes badgeFloat {
        0%, 100% { transform: translateY(0px) scale(1); }
        50% { transform: translateY(-3px) scale(1.05); }
    }
    
    @keyframes badgePulse {
        0%, 100% { transform: scale(1); box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3); }
        50% { transform: scale(1.1); box-shadow: 0 6px 25px rgba(220, 53, 69, 0.5); }
    }
    
    @keyframes badgeGlow {
        0% { box-shadow: 0 4px 15px rgba(0, 127, 255, 0.3); }
        100% { box-shadow: 0 6px 25px rgba(0, 127, 255, 0.6); }
    }
    
    .product-content {
        padding: 2rem 1.8rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    
    .product-title {
        font-family: 'Orbitron', sans-serif;
        font-weight: 700;
        color: var(--voltronix-accent);
        margin-bottom: 1rem;
        font-size: 1.25rem;
        line-height: 1.3;
        transition: color 0.3s ease;
    }
    
    .product-card:hover .product-title {
        color: var(--voltronix-primary);
    }
    
    .product-description {
        color: #666;
        margin-bottom: 1.2rem;
        line-height: 1.6;
        flex-grow: 1;
        font-size: 0.95rem;
    }
    
    .product-category {
        margin-bottom: 1rem;
    }
    
    .category-badge {
        background: rgba(0, 127, 255, 0.1);
        color: var(--voltronix-primary);
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .product-pricing {
        margin-bottom: 1.8rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .current-price {
        color: var(--voltronix-primary);
        font-size: 1.5rem;
        font-weight: 900;
        font-family: 'Orbitron', sans-serif;
    }
    
    .original-price {
        color: #999;
        text-decoration: line-through;
        font-size: 1.1rem;
    }
    
    .discounted-price {
        color: var(--voltronix-primary);
        font-size: 1.5rem;
        font-weight: 900;
        font-family: 'Orbitron', sans-serif;
    }
    
    .discount-badge {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 0.2rem 0.6rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    /* Enhanced Product Actions - Side by Side Buttons */
    .product-actions {
        margin-top: auto;
        display: flex;
        gap: 0.8rem;
        align-items: center;
    }
    
    .product-actions .btn {
        flex: 1;
        padding: 0.75rem 1rem;
        border-radius: 15px;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 45px;
    }
    
    .product-actions .btn-voltronix-primary {
        background: linear-gradient(135deg, var(--voltronix-primary), var(--voltronix-secondary));
        border: none;
        color: white;
    }
    
    .product-actions .btn-voltronix-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 127, 255, 0.3);
        color: white;
    }
    
    .product-actions .btn-voltronix-secondary,
    .product-actions .btn-add-to-cart {
        background: transparent;
        border: 2px solid var(--voltronix-primary);
        color: var(--voltronix-primary);
    }
    
    .product-actions .btn-voltronix-secondary:hover,
    .product-actions .btn-add-to-cart:hover {
        background: var(--voltronix-primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 127, 255, 0.3);
    }
    
    /* Mobile Responsive - Stack buttons vertically on small screens */
    @media (max-width: 576px) {
        .product-actions {
            flex-direction: column;
            gap: 0.6rem;
        }
        
        .product-actions .btn {
            width: 100%;
            flex: none;
        }
        
        .product-content {
            padding: 1.5rem 1.3rem;
        }
        
        .product-image {
            height: 200px;
        }
    }
    
    /* Medium screens - maintain side by side but smaller */
    @media (max-width: 768px) and (min-width: 577px) {
        .product-actions .btn {
            font-size: 0.8rem;
            padding: 0.6rem 0.8rem;
        }
    }
    
    /* Popular Products Slider */
    .popular-products-slider {
        background: var(--voltronix-gradient);
        padding: 5rem 0;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .popular-products-slider::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 70% 30%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        animation: pulse 3s ease-in-out infinite alternate;
    }
    
    .popular-products-slider .section-title,
    .popular-products-slider .section-subtitle {
        color: white;
    }
    
    .products-slider-track {
        display: flex;
        gap: 2rem;
        overflow-x: auto;
        padding: 1rem 0;
        scroll-behavior: smooth;
    }
    
    .product-slide {
        flex: 0 0 300px;
        min-width: 300px;
    }
    
    .product-card-slider {
        background: rgba(255, 255, 255, 0.95);
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-xl);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        backdrop-filter: blur(10px);
    }
    
    .product-card-slider:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }
    
    .product-card-slider .product-image {
        height: 180px;
    }
    
    .product-card-slider .product-content {
        padding: 1.2rem;
        color: var(--voltronix-accent);
    }
    
    .product-badge.popular {
        background: linear-gradient(135deg, #ff6b35, #f7931e);
        color: white;
        animation: pulse 2s ease-in-out infinite;
    }
    
    /* MODERN TRENDING PRODUCTS SECTION */
    .trending-products-section {
        background: var(--voltronix-light);
        padding: 5rem 0;
        position: relative;
    }
    
    .trending-product-card {
        background: white;
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        border: 2px solid rgba(0, 127, 255, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    
    .trending-product-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--voltronix-gradient);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .trending-product-card:hover {
        transform: translateY(-12px);
        box-shadow: var(--shadow-xl);
        border-color: var(--voltronix-primary);
    }
    
    .trending-product-card:hover::before {
        opacity: 1;
    }
    
    .trending-image-container {
        position: relative;
        height: 250px;
        overflow: hidden;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    }
    
    .trending-product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    
    .trending-product-card:hover .trending-product-image {
        transform: scale(1.08);
    }
    
    .trending-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(0, 127, 255, 0.1), rgba(35, 239, 255, 0.1));
        color: var(--voltronix-primary);
        font-size: 3rem;
    }
    
    .trending-badges {
        position: absolute;
        top: 1rem;
        left: 1rem;
        right: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        z-index: 2;
    }
    
    .trending-fire-badge {
        background: linear-gradient(135deg, #ff6b35, #f7931e);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.3rem;
        animation: pulse 2s ease-in-out infinite;
        box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        backdrop-filter: blur(10px);
    }
    
    .trending-discount-badge {
        background: #dc3545;
        color: white;
        padding: 0.3rem 0.6rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }
    
    .trending-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.7) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .trending-product-card:hover .trending-overlay {
        opacity: 1;
    }
    
    .trending-quick-actions {
        display: flex;
        gap: 1rem;
        transform: translateY(20px);
        transition: transform 0.3s ease;
    }
    
    .trending-product-card:hover .trending-quick-actions {
        transform: translateY(0);
    }
    
    .trending-action-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.9);
        color: var(--voltronix-primary);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }
    
    .trending-action-btn:hover {
        background: var(--voltronix-primary);
        color: white;
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(0, 127, 255, 0.3);
    }
    
    .trending-product-content {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .trending-product-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--voltronix-accent);
        margin-bottom: 0.75rem;
        line-height: 1.3;
        min-height: 2.6rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .trending-product-description {
        color: #666;
        margin-bottom: 1rem;
        line-height: 1.5;
        flex: 1;
        min-height: 3rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .trending-product-price {
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    .trending-old-price {
        text-decoration: line-through;
        color: #999;
        font-size: 1rem;
    }
    
    .trending-new-price,
    .trending-current-price {
        color: var(--voltronix-primary);
        font-weight: 700;
        font-size: 1.25rem;
    }
    
    .trending-save-badge {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .trending-product-actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }
    
    /* RTL Support for Trending Badges */
    [dir="rtl"] .trending-badges {
        left: auto;
        right: 1rem;
    }
    
    [dir="rtl"] .trending-fire-badge {
        flex-direction: row-reverse;
    }
    
    /* COMPREHENSIVE RESPONSIVE DESIGN */
    
    /* Large Desktop (1400px+) - Keep current desktop layout unchanged */
    @media (min-width: 1400px) {
        .container {
            max-width: 1320px;
        }
        
        .trending-image-container {
            height: 280px;
        }
        
        .section-title {
            font-size: 3rem;
        }
        
        .section-subtitle {
            font-size: 1.3rem;
        }
    }
    
    /* Desktop (1200px - 1399px) - Standard desktop view */
    @media (max-width: 1399px) and (min-width: 1200px) {
        .trending-image-container {
            height: 250px;
        }
    }
    
    /* Tablet Landscape (992px - 1199px) - Two column layout */
    @media (max-width: 1199px) and (min-width: 992px) {
        .trending-products-section {
            padding: 4rem 0;
        }
        
        .trending-image-container {
            height: 220px;
        }
        
        .section-title {
            font-size: 2.5rem;
        }
        
        .section-subtitle {
            font-size: 1.1rem;
        }
        
        .trending-product-title {
            font-size: 1.15rem;
        }
        
        .trending-product-content {
            padding: 1.25rem;
        }
    }
    
    /* Tablet Portrait (768px - 991px) - Two column layout with adjustments */
    @media (max-width: 991px) and (min-width: 768px) {
        .trending-products-section {
            padding: 3.5rem 0;
        }
        
        .trending-image-container {
            height: 200px;
        }
        
        .section-title {
            font-size: 2.2rem;
            margin-bottom: 1rem;
        }
        
        .section-subtitle {
            font-size: 1rem;
            margin-bottom: 2rem;
        }
        
        .trending-product-card {
            min-height: 420px;
        }
        
        .trending-product-content {
            padding: 1.25rem;
        }
        
        .trending-product-title {
            font-size: 1.1rem;
            min-height: 2.2rem;
        }
        
        .trending-product-description {
            font-size: 0.95rem;
            min-height: 2.85rem;
        }
        
        .trending-action-btn {
            width: 48px;
            height: 48px;
            font-size: 1.15rem;
        }
        
        .trending-fire-badge {
            padding: 0.35rem 0.7rem;
            font-size: 0.75rem;
        }
        
        .trending-product-actions {
            gap: 0.5rem;
        }
        
        .trending-product-actions .btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    }
    
    /* Mobile Large (576px - 767px) - Single column stacked layout */
    @media (max-width: 767px) and (min-width: 576px) {
        .trending-products-section {
            padding: 3rem 0;
        }
        
        .container {
            padding: 0 1rem;
        }
        
        .trending-image-container {
            height: 220px;
        }
        
        .section-title {
            font-size: 2rem;
            margin-bottom: 0.75rem;
        }
        
        .section-subtitle {
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }
        
        .trending-product-card {
            margin-bottom: 1.5rem;
            min-height: 400px;
        }
        
        .trending-product-content {
            padding: 1.25rem;
        }
        
        .trending-product-title {
            font-size: 1.2rem;
            min-height: 2.4rem;
            text-align: center;
        }
        
        .trending-product-description {
            font-size: 0.9rem;
            min-height: 2.7rem;
            text-align: center;
        }
        
        .trending-product-price {
            justify-content: center;
            margin-bottom: 1.25rem;
        }
        
        .trending-action-btn {
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
        }
        
        .trending-fire-badge {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }
        
        .trending-product-actions {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .trending-product-actions .btn {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border-radius: 12px;
        }
        
        .trending-badges {
            left: 0.75rem;
            right: 0.75rem;
        }
    }
    
    /* Mobile Small (320px - 575px) - Optimized for smallest screens */
    @media (max-width: 575px) {
        .trending-products-section {
            padding: 2.5rem 0;
        }
        
        .container {
            padding: 0 0.75rem;
        }
        
        .trending-image-container {
            height: 200px;
        }
        
        .section-title {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }
        
        .section-subtitle {
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        
        .trending-product-card {
            margin-bottom: 1.25rem;
            min-height: 380px;
        }
        
        .trending-product-content {
            padding: 1rem;
        }
        
        .trending-product-title {
            font-size: 1.1rem;
            min-height: 2.2rem;
            text-align: center;
        }
        
        .trending-product-description {
            font-size: 0.85rem;
            min-height: 2.55rem;
            text-align: center;
        }
        
        .trending-product-price {
            justify-content: center;
            margin-bottom: 1rem;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
        
        .trending-action-btn {
            width: 45px;
            height: 45px;
            font-size: 1.1rem;
        }
        
        .trending-fire-badge {
            padding: 0.3rem 0.6rem;
            font-size: 0.7rem;
        }
        
        .trending-discount-badge {
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
        }
        
        .trending-product-actions {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .trending-product-actions .btn {
            width: 100%;
            padding: 0.75rem;
            font-size: 0.95rem;
            border-radius: 10px;
        }
        
        .trending-badges {
            left: 0.5rem;
            right: 0.5rem;
            top: 0.75rem;
        }
        
        .trending-quick-actions {
            gap: 0.75rem;
        }
    }
    
    /* Ultra Small Mobile (below 320px) - Emergency fallback */
    @media (max-width: 319px) {
        .trending-products-section {
            padding: 2rem 0;
        }
        
        .container {
            padding: 0 0.5rem;
        }
        
        .trending-image-container {
            height: 180px;
        }
        
        .section-title {
            font-size: 1.5rem;
        }
        
        .section-subtitle {
            font-size: 0.8rem;
        }
        
        .trending-product-card {
            min-height: 360px;
        }
        
        .trending-product-content {
            padding: 0.75rem;
        }
        
        .trending-product-title {
            font-size: 1rem;
        }
        
        .trending-product-description {
            font-size: 0.8rem;
        }
        
        .trending-product-actions .btn {
            padding: 0.6rem;
            font-size: 0.9rem;
        }
    }
    
    /* About Section Enhancements */
    .about-section {
        background: var(--voltronix-light);
        padding: 6rem 0;
    }
    
    .about-icon-large {
        font-size: 12rem;
        background: var(--voltronix-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        opacity: 0.8;
        filter: drop-shadow(0 5px 15px rgba(0, 127, 255, 0.2));
    }
    
    .check-item {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        padding: 0.75rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    
    .check-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .check-item i {
        color: #28a745;
        font-size: 1.2rem;
        margin-right: 0.75rem;
    }
    
    /* Contact Section */
    .contact-section {
        background: white;
        padding: 6rem 0;
    }
    
    .contact-card {
        background: linear-gradient(145deg, #ffffff, #f8f9fa);
        border-radius: 20px;
        padding: 2.5rem 2rem;
        text-align: center;
        height: 100%;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .contact-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 127, 255, 0.15);
        border-color: var(--voltronix-primary);
    }
    
    .contact-icon {
        width: 90px;
        height: 90px;
        background: var(--voltronix-gradient);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2.2rem;
        color: white;
        box-shadow: 0 8px 25px rgba(0, 127, 255, 0.3);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.8rem;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
        }
        
        .section-title {
            font-size: 2.2rem;
        }
        
        .hero-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .hero-main-logo {
            max-width: 280px;
        }
        
        .stat-number {
            font-size: 2.8rem;
        }
        
        .category-card, .feature-card, .contact-card {
            padding: 2rem 1.5rem;
        }
        
        .about-icon-large {
            font-size: 8rem;
        }
        
        section {
            padding: 3rem 0;
        }
        
        .section-title {
            font-size: 2.2rem;
        }
    }
    
    /* GLOBAL CONSISTENCY STYLES */
    section {
        padding: 5rem 0;
    }
    
    .section-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 3rem;
        font-weight: 900;
        text-align: center;
        margin-bottom: 1rem;
        color: var(--voltronix-accent);
    }
    
    .section-subtitle {
        text-align: center;
        font-size: 1.2rem;
        color: #666;
        margin-bottom: 4rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }

    /* Testimonials Section */
    .testimonial-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 2rem;
        height: 100%;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        border: 2px solid transparent;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
    }

    .testimonial-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--voltronix-gradient);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }

    .testimonial-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 127, 255, 0.15);
        border-color: var(--voltronix-primary);
    }

    .testimonial-card:hover::before {
        transform: scaleX(1);
    }

    .testimonial-content {
        position: relative;
        z-index: 2;
    }

    .testimonial-text {
        font-size: 1rem;
        line-height: 1.6;
        color: #555;
        margin-bottom: 1.5rem;
        font-style: italic;
    }

    .testimonial-author {
        border-top: 1px solid rgba(0, 127, 255, 0.1);
        padding-top: 1rem;
    }

    .testimonial-rating {
        font-size: 1.1rem;
    }
</style>
@endpush

@section('content')
<!-- DYNAMIC HERO SLIDER - Backend Ready -->
<section class="hero-slider">
    <div class="slider-container">
        {{-- Dynamic Hero Slides --}}
        @forelse($sections->get('hero', collect()) as $index => $slide)
            <div class="slide slide-{{ $index + 1 }} {{ $loop->first ? 'active' : '' }}"
                 @if($slide->image_path) 
                     style="background-image: url('{{ asset('storage/' . $slide->image_path) }}'); background-size: cover; background-position: center;" 
                 @elseif($slide->settings && isset($slide->settings['background_gradient']))
                     style="background: {{ $slide->settings['background_gradient'] }};"
                 @endif>
                <div class="slide-content">
                    <h1 class="slide-title">{{ $slide->getTranslation('title', app()->getLocale()) }}</h1>
                    <p class="slide-subtitle">{{ $slide->getTranslation('subtitle', app()->getLocale()) }}</p>
                    <div class="slide-buttons">
                        @if($slide->getTranslation('button_text', app()->getLocale()))
                            <a href="{{ $slide->link_url ?: '#' }}" class="btn btn-voltronix-primary">
                                <i class="bi bi-lightning {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ $slide->getTranslation('button_text', app()->getLocale()) }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            {{-- Fallback to static content --}}
        <!-- Slide 1: Welcome -->
        <div class="slide slide-1 active">
            <div class="slide-content">
                <h1 class="slide-title" >{{ __('app.hero.welcome_title') }}</h1>
                <p class="slide-subtitle">{{ __('app.hero.welcome_subtitle') }}</p>
                <div class="slide-buttons">
                    <a href="{{ route('categories.index') }}" class="btn btn-voltronix-primary">
                        <i class="bi bi-shop {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.hero.shop_now') }}
                    </a>
                    <a href="#categories" class="btn btn-voltronix-outline">
                        <i class="bi bi-arrow-down {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.hero.explore') }}
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Slide 2: Premium Quality -->
        <div class="slide slide-2">
            <div class="slide-content">
                <h1 class="slide-title">{{ __('app.hero.quality_title') }}</h1>
                <p class="slide-subtitle">{{ __('app.hero.quality_subtitle') }}</p>
                <div class="slide-buttons">
                    <a href="{{ route('products.index') }}" class="btn btn-voltronix-primary">
                        <i class="bi bi-star {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.hero.view_products') }}
                    </a>
                    <a href="#features" class="btn btn-voltronix-outline">
                        <i class="bi bi-info-circle {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.hero.learn_more') }}
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Slide 3: Secure & Fast -->
        <div class="slide slide-3">
            <div class="slide-content">
                <h1 class="slide-title">{{ __('app.hero.secure_title') }}</h1>
                <p class="slide-subtitle">{{ __('app.hero.secure_subtitle') }}</p>
                <div class="slide-buttons">
                    <a href="{{ route('offers.index') }}" class="btn btn-voltronix-primary">
                        <i class="bi bi-lightning {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.hero.special_offers') }}
                    </a>
                    <a href="#contact" class="btn btn-voltronix-outline">
                        <i class="bi bi-headset {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.hero.contact_us') }}
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>
    
    <!-- Slider Navigation -->
    <div class="slider-nav slider-prev" onclick="changeSlide(-1)">
        <i class="bi bi-chevron-left"></i>
    </div>
    <div class="slider-nav slider-next" onclick="changeSlide(1)">
        <i class="bi bi-chevron-right"></i>
    </div>
    
    <!-- Slider Dots -->
    <div class="slider-controls">
        @if($sections->get('hero', collect())->count() > 0)
            @foreach($sections->get('hero', []) as $index => $slide)
                <div class="slider-dot {{ $loop->first ? 'active' : '' }}" onclick="currentSlide({{ $index + 1 }})"></div>
            @endforeach
        @else
            <div class="slider-dot active" onclick="currentSlide(1)"></div>
            <div class="slider-dot" onclick="currentSlide(2)"></div>
            <div class="slider-dot" onclick="currentSlide(3)"></div>
        @endif
    </div>
</section>

<!-- CATEGORIES SECTION - CLEAN PROFESSIONAL DESIGN -->
<section id="categories" class="categories-section">
    <div class="container">
        <h2 class="showcase-title">{{ __('app.categories.title') }}</h2>
        <p class="showcase-subtitle">{{ __('app.categories.subtitle') }}</p>
        
        <div class="categories-grid">
            {{-- Dynamic categories with modern card design --}}
            @forelse($categories as $category)
                <a href="{{ route('categories.show', $category->slug) }}" class="category-card-modern" style="text-decoration: none;">
                    {{-- Category Image or Icon --}}
                    @if($category->thumbnail)
                        <div class="category-image-wrapper">
                            <img class="category-card-image" 
                                 src="{{ asset('storage/' . $category->thumbnail) }}" 
                                 alt="{{ $category->getTranslation('name', app()->getLocale()) }}">
                        </div>
                    @else
                        <div class="category-icon-wrapper">
                            <i class="bi bi-grid-3x3-gap category-icon-large"></i>
                        </div>
                    @endif
                    
                    {{-- Category Content --}}
                    <div class="category-card-content">
                        <h3 class="category-card-title">
                            {{ $category->getTranslation('name', app()->getLocale()) }}
                        </h3>
                        
                        @if($category->description)
                            <p class="category-card-description">
                                {{ Str::limit($category->getTranslation('description', app()->getLocale()), 80) }}
                            </p>
                        @endif
                        
                        <div class="category-card-meta">
                            <span class="category-product-count">
                                <i class="bi bi-box-seam"></i>
                                {{ $category->products_count }} {{ __('app.categories.products_count') }}
                            </span>
                            <span class="category-card-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                {{-- Fallback static categories --}}
                <a href="{{ route('categories.index') }}" class="category-card-modern" style="text-decoration: none;">
                    <div class="category-icon-wrapper">
                        <i class="bi bi-laptop category-icon-large"></i>
                    </div>
                    <div class="category-card-content">
                        <h3 class="category-card-title">{{ __('app.categories.software.title') }}</h3>
                        <p class="category-card-description">{{ __('app.categories.software.description') }}</p>
                        <div class="category-card-meta">
                            <span class="category-product-count">
                                <i class="bi bi-box-seam"></i>
                                24 {{ __('app.categories.products_count') }}
                            </span>
                            <span class="category-card-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('categories.index') }}" class="category-card-modern" style="text-decoration: none;">
                    <div class="category-icon-wrapper">
                        <i class="bi bi-controller category-icon-large"></i>
                    </div>
                    <div class="category-card-content">
                        <h3 class="category-card-title">{{ __('app.categories.gaming.title') }}</h3>
                        <p class="category-card-description">{{ __('app.categories.gaming.description') }}</p>
                        <div class="category-card-meta">
                            <span class="category-product-count">
                                <i class="bi bi-box-seam"></i>
                                18 {{ __('app.categories.products_count') }}
                            </span>
                            <span class="category-card-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('categories.index') }}" class="category-card-modern" style="text-decoration: none;">
                    <div class="category-icon-wrapper">
                        <i class="bi bi-tools category-icon-large"></i>
                    </div>
                    <div class="category-card-content">
                        <h3 class="category-card-title">{{ __('app.categories.tools.title') }}</h3>
                        <p class="category-card-description">{{ __('app.categories.tools.description') }}</p>
                        <div class="category-card-meta">
                            <span class="category-product-count">
                                <i class="bi bi-box-seam"></i>
                                32 {{ __('app.categories.products_count') }}
                            </span>
                            <span class="category-card-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('categories.index') }}" class="category-card-modern" style="text-decoration: none;">
                    <div class="category-icon-wrapper">
                        <i class="bi bi-shield-check category-icon-large"></i>
                    </div>
                    <div class="category-card-content">
                        <h3 class="category-card-title">{{ __('app.categories.security.title') }}</h3>
                        <p class="category-card-description">{{ __('app.categories.security.description') }}</p>
                        <div class="category-card-meta">
                            <span class="category-product-count">
                                <i class="bi bi-box-seam"></i>
                                15 {{ __('app.categories.products_count') }}
                            </span>
                            <span class="category-card-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('categories.index') }}" class="category-card-modern" style="text-decoration: none;">
                    <div class="category-icon-wrapper">
                        <i class="bi bi-camera-video category-icon-large"></i>
                    </div>
                    <div class="category-card-content">
                        <h3 class="category-card-title">{{ __('app.categories.streaming.title') }}</h3>
                        <p class="category-card-description">{{ __('app.categories.streaming.description') }}</p>
                        <div class="category-card-meta">
                            <span class="category-product-count">
                                <i class="bi bi-box-seam"></i>
                                12 {{ __('app.categories.products_count') }}
                            </span>
                            <span class="category-card-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('categories.index') }}" class="category-card-modern" style="text-decoration: none;">
                    <div class="category-icon-wrapper">
                        <i class="bi bi-mortarboard category-icon-large"></i>
                    </div>
                    <div class="category-card-content">
                        <h3 class="category-card-title">{{ __('app.categories.education.title') }}</h3>
                        <p class="category-card-description">{{ __('app.categories.education.description') }}</p>
                        <div class="category-card-meta">
                            <span class="category-product-count">
                                <i class="bi bi-box-seam"></i>
                                28 {{ __('app.categories.products_count') }}
                            </span>
                            <span class="category-card-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
            @endforelse
        </div>
    </div>
</section>

<!-- LATEST PRODUCTS SECTION - Backend Ready -->
<section class="latest-products-section">
    <div class="container">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <h2 class="section-title">
                <i class="bi bi-clock {{ app()->getLocale() == 'ar' ? 'ms-3' : 'me-3' }}"></i>
                {{ __('app.products.latest_title') }}
            </h2>
            <p class="section-subtitle">{{ __('app.products.latest_subtitle') }}</p>
        </div>

        <!-- Products Grid - Backend Ready Structure -->
        <div class="row g-4">
            {{-- Dynamic Latest Products --}}
            @forelse($latestProducts->take(6) as $product)
                <div class="col-lg-4 col-md-6">
                    <div class="product-card">
                        <div class="product-image">
                            @if($product->thumbnail)
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->getTranslation('name', app()->getLocale()) }}" class="img-fluid">
                            @else
                                <div class="product-placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                            @if($product->hasDiscount())
                                <div class="product-badge sale">{{ $product->discount_percentage }}% {{ __('app.common.off') }}</div>
                            @elseif($product->is_new)
                                <div class="product-badge new">{{ __('app.common.new') }}</div>
                            @elseif($product->is_featured)
                                <div class="product-badge featured">{{ __('app.common.featured') }}</div>
                            @endif
                        </div>
                        <div class="product-content">
                            <h4 class="product-title">{{ $product->getTranslation('name', app()->getLocale()) }}</h4>
                            <p class="product-description">{{ Str::limit($product->getTranslation('description', app()->getLocale()), 100) }}</p>
                            <div class="product-category">
                                <span class="category-badge">{{ $product->category->getTranslation('name', app()->getLocale()) }}</span>
                            </div>
                            <div class="product-pricing">
                                @if($product->hasDiscount())
                                    <span class="original-price">{{ currency_format($product->price) }}</span>
                                    <span class="discounted-price">{{ currency_format($product->discount_price) }}</span>
                                    <span class="discount-badge">{{ __('app.common.save') }} {{ safe_subtract($product->price, $product->discount_price) }}</span>
                                @else
                                    <span class="current-price">{{ currency_format($product->price) }}</span>
                                @endif
                            </div>
                            <div class="product-actions">
                                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-voltronix-primary">
                                    <i class="bi bi-eye {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                    {{ __('app.products.view_details') }}
                                </a>
                                <button onclick="addToCart({{ $product->id }})" class="btn btn-voltronix-secondary btn-add-to-cart">
                                    <i class="bi bi-cart-plus {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                    {{ __('app.products.add_to_cart') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
            
            {{-- Static placeholder until backend is ready --}}
            <div class="col-lg-4 col-md-6">
                <div class="product-card">
                    <div class="product-image">
                        <div class="product-placeholder">
                            <i class="bi bi-laptop"></i>
                        </div>
                        <div class="product-badge new">{{ __('app.common.new') }}</div>
                    </div>
                    <div class="product-content">
                        <h4 class="product-title">{{ __('app.products.sample_software') }}</h4>
                        <p class="product-description">{{ __('app.products.sample_software_desc') }}</p>
                        <div class="product-pricing">
                            <span class="current-price" data-price="99.99">{{ currency_format(99.99) }}</span>
                        </div>
                        <div class="product-actions">
                            <a href="{{ route('products.index') }}" class="btn btn-voltronix-primary">
                                <i class="bi bi-eye {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('app.products.view_details') }}
                            </a>
                            <button onclick="addToCart(1)" class="btn btn-add-to-cart">
                                <i class="bi bi-cart-plus {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('app.common.add_to_cart') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="product-card">
                    <div class="product-image">
                        <div class="product-placeholder">
                            <i class="bi bi-controller"></i>
                        </div>
                        <div class="product-badge sale">25% {{ __('app.common.off') }}</div>
                    </div>
                    <div class="product-content">
                        <h4 class="product-title">{{ __('app.products.sample_game') }}</h4>
                        <p class="product-description">{{ __('app.products.sample_game_desc') }}</p>
                        <div class="product-pricing">
                            <span class="original-price" data-price="79.99">{{ currency_format(79.99) }}</span>
                            <span class="discounted-price" data-price="59.99">{{ currency_format(59.99) }}</span>
                            <span class="discount-badge">{{ __('app.common.save') }} {{ currency_format(20.00) }}</span>
                        </div>
                        <div class="product-actions">
                            <a href="{{ route('products.index') }}" class="btn btn-voltronix-primary">
                                <i class="bi bi-eye {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('app.products.view_details') }}
                            </a>
                            <button onclick="addToCart(2)" class="btn btn-add-to-cart">
                                <i class="bi bi-cart-plus {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('app.common.add_to_cart') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="product-card">
                    <div class="product-image">
                        <div class="product-placeholder">
                            <i class="bi bi-tools"></i>
                        </div>
                        <div class="product-badge featured">{{ __('app.common.featured') }}</div>
                    </div>
                    <div class="product-content">
                        <h4 class="product-title">{{ __('app.products.sample_tool') }}</h4>
                        <p class="product-description">{{ __('app.products.sample_tool_desc') }}</p>
                        <div class="product-pricing">
                            <span class="current-price">$149.99</span>
                        </div>
                        <div class="product-actions">
                            <a href="{{ route('products.index') }}" class="btn btn-voltronix-primary">
                                <i class="bi bi-eye {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('app.products.view_details') }}
                            </a>
                            <button onclick="addToCart(3)" class="btn btn-add-to-cart">
                                <i class="bi bi-cart-plus {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('app.common.add_to_cart') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- View All Products -->
        <div class="text-center mt-5">
            <a href="{{ route('products.index') }}" class="btn btn-voltronix-secondary btn-lg">
                <i class="bi bi-grid {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('app.products.view_all') }}
            </a>
        </div>
        @endforelse
    </div>
</section>

<!-- Special Offers Section - Backend Ready -->
<section class="special-offers-section">
    <div class="container">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <h2 class="section-title">
                <i class="bi bi-lightning-charge {{ app()->getLocale() == 'ar' ? 'ms-3' : 'me-3' }}"></i>
                {{ __('app.offers.title') }}
            </h2>
            <p class="section-subtitle">{{ __('app.offers.subtitle') }}</p>
        </div>

        <!-- Offers Grid - Real Discounted Products -->
        <div class="row g-4">
            {{-- Real Special Offers from Database --}}
            @forelse($specialOffers as $product)
                <div class="col-lg-4 col-md-6">
                    <div class="offer-card">
                        <div class="offer-badge">{{ $product->discount_percentage }}% {{ __('app.common.off') }}</div>
                        @if($product->thumbnail)
                            <div class="offer-image">
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->getTranslation('name', app()->getLocale()) }}" class="img-fluid">
                            </div>
                        @else
                            <div class="offer-image">
                                <div class="product-placeholder">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                            </div>
                        @endif
                        <div class="offer-content">
                            <h4>{{ $product->getTranslation('name', app()->getLocale()) }}</h4>
                            <p>{{ Str::limit($product->getTranslation('description', app()->getLocale()), 100) }}</p>
                            <div class="offer-pricing">
                                <span class="original-price">{{ currency_format($product->price) }}</span>
                                <span class="discounted-price">{{ currency_format($product->discount_price) }}</span>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-voltronix-primary flex-fill">
                                    <i class="bi bi-tag {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                    {{ __('app.offers.claim_offer') }}
                                </a>
                                <button onclick="addToCart({{ $product->id }})" class="btn btn-voltronix-secondary">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Fallback to static content --}}
            <div class="col-lg-4 col-md-6">
                <div class="offer-card">
                    <div class="offer-badge">25% {{ __('app.common.off') }}</div>
                    <div class="offer-content">
                        <h4>{{ __('app.offers.software_bundle') }}</h4>
                        <p>{{ __('app.offers.software_bundle_desc') }}</p>
                        <div class="offer-pricing">
                            <span class="original-price">$199</span>
                            <span class="discounted-price">$149</span>
                        </div>
                        <a href="{{ route('offers.index') }}" class="btn btn-voltronix-primary">
                            <i class="bi bi-tag {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('app.offers.claim_offer') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="offer-card">
                    <div class="offer-badge">50% {{ __('app.common.off') }}</div>
                    <div class="offer-content">
                        <h4>{{ __('app.offers.gaming_pack') }}</h4>
                        <p>{{ __('app.offers.gaming_pack_desc') }}</p>
                        <div class="offer-pricing">
                            <span class="original-price">$299</span>
                            <span class="discounted-price">$149</span>
                        </div>
                        <a href="{{ route('offers.index') }}" class="btn btn-voltronix-primary">
                            <i class="bi bi-controller {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('app.offers.claim_offer') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="offer-card">
                    <div class="offer-badge">{{ __('app.offers.limited') }}</div>
                    <div class="offer-content">
                        <h4>{{ __('app.offers.premium_tools') }}</h4>
                        <p>{{ __('app.offers.premium_tools_desc') }}</p>
                        <div class="offer-pricing">
                            <span class="original-price">$399</span>
                            <span class="discounted-price">$299</span>
                        </div>
                        <a href="{{ route('offers.index') }}" class="btn btn-voltronix-primary">
                            <i class="bi bi-tools {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('app.offers.claim_offer') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center mt-5">
            <a href="{{ route('offers.index') }}" class="btn btn-voltronix-secondary btn-lg">
                <i class="bi bi-eye {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('app.offers.view_all') }}
            </a>
        </div>
        @endforelse
    </div>
</section>

<!-- TRENDING NOW - MODERN CARD SHOWCASE -->
<section class="trending-products-section">
    <div class="container">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <h2 class="section-title">
                <i class="bi bi-fire {{ app()->getLocale() == 'ar' ? 'ms-3' : 'me-3' }}"></i>
                {{ __('app.products.trending_title') }}
            </h2>
            <p class="section-subtitle">{{ __('app.products.trending_subtitle') }}</p>
        </div>

        <!-- Trending Products Grid -->
        <div class="row g-4" id="trendingGrid">
            @forelse($trendingProducts->take(6) as $index => $product)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="trending-product-card">
                        <div class="trending-image-container">
                            @if($product->thumbnail)
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->getTranslation('name', app()->getLocale()) }}" class="trending-product-image">
                            @else
                                <div class="trending-placeholder">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                            @endif
                            <div class="trending-badges">
                                <div class="trending-fire-badge">
                                    <i class="bi bi-fire"></i>
                                    <span>{{ __('app.products.trending') }}</span>
                                </div>
                                @if($product->hasDiscount())
                                    <div class="trending-discount-badge">-{{ $product->discount_percentage }}%</div>
                                @endif
                            </div>
                            <div class="trending-overlay">
                                <div class="trending-quick-actions">
                                    <a href="{{ route('products.show', $product->slug) }}" class="trending-action-btn trending-view-btn" title="{{ __('app.products.view_details') }}">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button onclick="addToCart({{ $product->id }})" class="trending-action-btn trending-cart-btn" title="{{ __('app.common.add_to_cart') }}">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="trending-product-content">
                            <h4 class="trending-product-title">{{ $product->getTranslation('name', app()->getLocale()) }}</h4>
                            <p class="trending-product-description">{{ Str::limit($product->getTranslation('description', app()->getLocale()), 80) }}</p>
                            <div class="trending-product-price">
                                @if($product->hasDiscount())
                                    <span class="trending-old-price">{{ currency_format($product->price) }}</span>
                                    <span class="trending-new-price">{{ currency_format($product->discount_price) }}</span>
                                    <span class="trending-save-badge">{{ __('app.common.save') }} {{ $product->discount_percentage }}%</span>
                                @else
                                    <span class="trending-current-price">{{ currency_format($product->price) }}</span>
                                @endif
                            </div>
                            <div class="trending-product-actions">
                                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-voltronix-primary btn-sm flex-fill">
                                    <i class="bi bi-eye {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                    {{ __('app.products.view_details') }}
                                </a>
                                <button onclick="addToCart({{ $product->id }})" class="btn btn-voltronix-secondary btn-sm">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Fallback Static Products -->
                @for($i = 0; $i < 6; $i++)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                    <div class="trending-product-card">
                        <div class="trending-image-container">
                            <div class="trending-placeholder">
                                <i class="bi bi-{{ ['laptop', 'controller', 'tools', 'shield-check', 'camera-video', 'mortarboard'][$i] }}"></i>
                            </div>
                            <div class="trending-badges">
                                <div class="trending-fire-badge">
                                    <i class="bi bi-fire"></i>
                                    <span>{{ __('app.products.trending') }}</span>
                                </div>
                            </div>
                            <div class="trending-overlay">
                                <div class="trending-quick-actions">
                                    <a href="{{ route('products.index') }}" class="trending-action-btn trending-view-btn">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="trending-action-btn trending-cart-btn">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="trending-product-content">
                            <h4 class="trending-product-title">{{ __('app.products.sample_' . ['software', 'game', 'tool', 'software', 'game', 'tool'][$i % 3]) }}</h4>
                            <p class="trending-product-description">{{ __('app.products.sample_' . ['software', 'game', 'tool', 'software', 'game', 'tool'][$i % 3] . '_desc') }}</p>
                            <div class="trending-product-price">
                                <span class="trending-current-price">${{ [149, 89, 199, 79, 299, 129][$i] }}</span>
                            </div>
                            <div class="trending-product-actions">
                                <a href="{{ route('products.index') }}" class="btn btn-voltronix-primary btn-sm flex-fill">
                                    <i class="bi bi-eye {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                    {{ __('app.products.view_details') }}
                                </a>
                                <button class="btn btn-voltronix-secondary btn-sm">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            @endforelse
        </div>

        <!-- View All Button -->
        <div class="text-center mt-5">
            <a href="{{ route('products.index') }}" class="btn btn-voltronix-secondary btn-lg">
                <i class="bi bi-grid {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('app.products.view_all') }}
            </a>
        </div>
    </div>
</section>

<!-- DYNAMIC WHY CHOOSE VOLTRONIX SECTION - Backend Ready -->
<section id="features" class="ultra-features">
    <div class="container">
        <h2 class="features-title">{{ __('app.features.title') }}</h2>
        <p class="features-subtitle">{{ __('app.features.subtitle') }}</p>
        
        <div class="row g-5">
            {{-- Dynamic Features --}}
            @forelse($sections->get('banner', collect())->where('title', 'Features') as $featureSection)
                @php
                    $features = $featureSection->content['features'] ?? [];
                @endphp
                @foreach($features as $feature)
                    <div class="col-lg-4 col-md-6">
                        <div class="ultra-feature-card">
                            <div class="feature-icon-ultra">
                                <i class="bi bi-{{ $feature['icon'] ?? 'star' }}"></i>
                            </div>
                            <h3 class="feature-title-ultra">
                                @if(is_array($feature['title'] ?? null))
                                    {{ $feature['title'][app()->getLocale()] ?? $feature['title']['en'] ?? '' }}
                                @else
                                    {{ $feature['title'] ?? '' }}
                                @endif
                            </h3>
                            <p class="feature-desc-ultra">
                                @if(is_array($feature['description'] ?? null))
                                    {{ $feature['description'][app()->getLocale()] ?? $feature['description']['en'] ?? '' }}
                                @else
                                    {{ $feature['description'] ?? '' }}
                                @endif
                            </p>
                            @if(isset($feature['highlight']))
                                <div class="feature-highlight">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>
                                        @if(is_array($feature['highlight'] ?? null))
                                            {{ $feature['highlight'][app()->getLocale()] ?? $feature['highlight']['en'] ?? '' }}
                                        @else
                                            {{ $feature['highlight'] ?? '' }}
                                        @endif
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @empty
                {{-- Fallback to static content --}}
            <div class="col-lg-4 col-md-6">
                <div class="ultra-feature-card">
                    <div class="feature-icon-ultra">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h3 class="feature-title-ultra">{{ __('app.features.secure_payments.title') }}</h3>
                    <p class="feature-desc-ultra">{{ __('app.features.secure_payments.description') }}</p>
                    <div class="feature-highlight">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>{{ __('app.features.ssl_encryption') }}</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="ultra-feature-card">
                    <div class="feature-icon-ultra">
                        <i class="bi bi-lightning-charge"></i>
                    </div>
                    <h3 class="feature-title-ultra">{{ __('app.features.instant_delivery.title') }}</h3>
                    <p class="feature-desc-ultra">{{ __('app.features.instant_delivery.description') }}</p>
                    <div class="feature-highlight">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>{{ __('app.features.immediate_access') }}</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="ultra-feature-card">
                    <div class="feature-icon-ultra">
                        <i class="bi bi-award"></i>
                    </div>
                    <h3 class="feature-title-ultra">{{ __('app.features.trusted_services.title') }}</h3>
                    <p class="feature-desc-ultra">{{ __('app.features.trusted_services.description') }}</p>
                    <div class="feature-highlight">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>{{ __('app.features.verified_products') }}</span>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Dynamic Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4">
            @forelse($sections->get('stats', collect()) as $statsSection)
                @php
                    $sectionStats = $statsSection->content['stats'] ?? [];
                @endphp
                @foreach($sectionStats as $stat)
                    <div class="col-lg-3 col-md-6">
                        <div class="stat-card">
                            <span class="stat-number">{{ $stat['value'] ?? '0' }}</span>
                            <span class="stat-label">
                                @if(is_array($stat['label'] ?? null))
                                    {{ $stat['label'][app()->getLocale()] ?? $stat['label']['en'] ?? '' }}
                                @else
                                    {{ $stat['label'] ?? '' }}
                                @endif
                            </span>
                        </div>
                    </div>
                @endforeach
            @empty
                {{-- Fallback to dynamic stats from database --}}
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <span class="stat-number" data-target="{{ $stats['customers'] ?? 0 }}">{{ number_format($stats['customers'] ?? 0) }}+</span>
                        <span class="stat-label">{{ __('app.stats.customers') }}</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <span class="stat-number" data-target="{{ $stats['products'] ?? 0 }}">{{ number_format($stats['products'] ?? 0) }}+</span>
                        <span class="stat-label">{{ __('app.stats.products') }}</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <span class="stat-number" data-target="{{ $stats['orders'] ?? 0 }}">{{ number_format($stats['orders'] ?? 0) }}+</span>
                        <span class="stat-label">{{ __('app.stats.orders') }}</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">{{ __('app.stats.support') }}</span>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Dynamic About Section -->
<section id="about" class="about-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4">
                @forelse($sections->get('banner', collect())->where('title', 'About') as $aboutSection)
                    <h2 class="section-title text-{{ app()->getLocale() == 'ar' ? 'end' : 'start' }}">{{ $aboutSection->getTranslation('title', app()->getLocale()) }}</h2>
                    <p class="lead mb-4">{{ $aboutSection->getTranslation('subtitle', app()->getLocale()) }}</p>
                    <p class="mb-4">{{ $aboutSection->getTranslation('description', app()->getLocale()) }}</p>
                    @php
                        $features = $aboutSection->content['features'] ?? [];
                    @endphp
                    <div class="row g-3">
                        @foreach($features as $feature)
                            <div class="col-12">
                                <div class="check-item">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>
                                        @if(is_array($feature['text'] ?? null))
                                            {{ $feature['text'][app()->getLocale()] ?? $feature['text']['en'] ?? '' }}
                                        @else
                                            {{ $feature['text'] ?? '' }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @empty
                    {{-- Fallback to static content --}}
                    <h2 class="section-title text-{{ app()->getLocale() == 'ar' ? 'end' : 'start' }}">{{ __('app.about.title') }}</h2>
                    <p class="lead mb-4">{{ __('app.about.lead') }}</p>
                    <p class="mb-4">{{ __('app.about.description') }}</p>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="check-item">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>{{ __('app.about.verified_products') }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="check-item">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>{{ __('app.about.secure_platform') }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="check-item">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>{{ __('app.about.support_24_7') }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="check-item">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>{{ __('app.about.best_prices') }}</span>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="col-lg-6 text-center">
                <div class="p-4">
                    @forelse($sections->get('banner', collect())->where('title', 'About') as $aboutSection)
                        @if($aboutSection->image_path)
                            <img src="{{ asset('storage/' . $aboutSection->image_path) }}" alt="{{ $aboutSection->getTranslation('title', app()->getLocale()) }}" class="img-fluid about-image">
                        @else
                            <i class="bi bi-building about-icon-large"></i>
                        @endif
                    @empty
                        <i class="bi bi-building about-icon-large"></i>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Dynamic Testimonials Section -->
<section class="testimonials-section py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">{{ __('app.testimonials.title') }}</h2>
            <p class="section-subtitle">{{ __('app.testimonials.subtitle') }}</p>
        </div>
        
        @if(isset($testimonials) && $testimonials->isNotEmpty())
            <div class="row g-4">
                @foreach($testimonials->take(3) as $review)
                    <div class="col-lg-4 col-md-6">
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <div class="testimonial-rating mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning"></i>
                                    @endfor
                                </div>
                                <p class="testimonial-text">"{{ $review->comment }}"</p>
                                <div class="testimonial-author">
                                    <strong>{{ $review->user->name }}</strong>
                                    <small class="text-muted d-block">{{ __('app.testimonials.verified_customer') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center">
                <p class="text-muted">{{ __('app.testimonials.no_reviews') }}</p>
            </div>
        @endif
    </div>
</section>

<!-- Dynamic Contact Section -->
<section id="contact" class="contact-section">
    <div class="container">
        @forelse($sections->get('banner', collect())->where('title', 'Contact') as $contactSection)
            <h2 class="section-title">{{ $contactSection->getTranslation('title', app()->getLocale()) }}</h2>
            <p class="section-subtitle">{{ $contactSection->getTranslation('subtitle', app()->getLocale()) }}</p>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row g-4">
                        @php
                            $contacts = $contactSection->content['contacts'] ?? [];
                        @endphp
                        @foreach($contacts as $contact)
                            <div class="col-md-4">
                                <div class="contact-card">
                                    <div class="contact-icon">
                                        <i class="bi bi-{{ $contact['icon'] ?? 'envelope-fill' }}"></i>
                                    </div>
                                    <h5>
                                        @if(is_array($contact['title'] ?? null))
                                            {{ $contact['title'][app()->getLocale()] ?? $contact['title']['en'] ?? '' }}
                                        @else
                                            {{ $contact['title'] ?? '' }}
                                        @endif
                                    </h5>
                                    <p class="text-muted">
                                        @if(is_array($contact['description'] ?? null))
                                            {{ $contact['description'][app()->getLocale()] ?? $contact['description']['en'] ?? '' }}
                                        @else
                                            {{ $contact['description'] ?? '' }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-5">
                        <a href="{{ $contactSection->link_url ?: '#' }}" class="btn btn-voltronix-primary btn-lg">
                            <i class="bi bi-chat-left-dots {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                            {{ $contactSection->getTranslation('button_text', app()->getLocale()) ?: __('app.contact.contact_support') }}
                        </a>
                    </div>
                </div>
            </div>
        @empty
            {{-- Fallback to static content --}}
            <h2 class="section-title">{{ __('app.contact.title') }}</h2>
            <p class="section-subtitle">{{ __('app.contact.subtitle') }}</p>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="contact-card">
                                <div class="contact-icon">
                                    <i class="bi bi-envelope-fill"></i>
                                </div>
                                <h5>{{ __('app.contact.email.title') }}</h5>
                                <p class="text-muted">{{ setting('contact_email') ?: __('app.contact.email.address') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="contact-card">
                                <div class="contact-icon">
                                    <i class="bi bi-chat-dots-fill"></i>
                                </div>
                                <h5>{{ __('app.contact.chat.title') }}</h5>
                                <p class="text-muted">{{ __('app.contact.chat.availability') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="contact-card">
                                <div class="contact-icon">
                                    <i class="bi bi-headset"></i>
                                </div>
                                <h5>{{ __('app.contact.support.title') }}</h5>
                                <p class="text-muted">{{ __('app.contact.support.description') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-5">
                        <a href="#" class="btn btn-voltronix-primary btn-lg">
                            <i class="bi bi-chat-left-dots {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.contact.contact_support') }}
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Hero Slider Functionality
    let currentSlideIndex = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.slider-dot');
    const totalSlides = slides.length;
    let autoSlideInterval;

    function showSlide(index) {
        // Remove active class from all slides and dots
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        // Add active class to current slide and dot
        slides[index].classList.add('active');
        dots[index].classList.add('active');
        
        currentSlideIndex = index;
    }

    function nextSlide() {
        const nextIndex = (currentSlideIndex + 1) % totalSlides;
        showSlide(nextIndex);
    }

    function prevSlide() {
        const prevIndex = (currentSlideIndex - 1 + totalSlides) % totalSlides;
        showSlide(prevIndex);
    }

    function changeSlide(direction) {
        if (direction === 1) {
            nextSlide();
        } else {
            prevSlide();
        }
        resetAutoSlide();
    }

    function currentSlide(index) {
        showSlide(index - 1);
        resetAutoSlide();
    }

    function startAutoSlide() {
        autoSlideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
    }

    function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        startAutoSlide();
    }

    // Initialize slider
    document.addEventListener('DOMContentLoaded', function() {
        showSlide(0);
        startAutoSlide();
        
        // Pause auto-slide on hover
        const sliderContainer = document.querySelector('.hero-slider');
        sliderContainer.addEventListener('mouseenter', () => {
            clearInterval(autoSlideInterval);
        });
        
        sliderContainer.addEventListener('mouseleave', () => {
            startAutoSlide();
        });
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
            changeSlide(-1);
        } else if (e.key === 'ArrowRight') {
            changeSlide(1);
        }
    });
</script>
@endpush

@push('scripts')
<script>
    // Add scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all cards and sections
    document.querySelectorAll('.category-card, .feature-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });

    // Counter animation for stats
    function animateCounter(element, target) {
        let current = 0;
        const increment = target / 100;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            
            if (target >= 1000) {
                element.textContent = Math.floor(current / 1000) + 'K+';
            } else if (target >= 100) {
                element.textContent = Math.floor(current) + '%';
            } else {
                element.textContent = Math.floor(current) + '/7';
            }
        }, 20);
    }

    // Animate stats when they come into view
    const statsObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumbers = entry.target.querySelectorAll('.stat-number');
                statNumbers.forEach((stat, index) => {
                    const targets = [10000, 500, 99.9, 24];
                    setTimeout(() => {
                        animateCounter(stat, targets[index]);
                    }, index * 200);
                });
                statsObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
        statsObserver.observe(statsSection);
    }

    // Trending Products Slider
    let currentSlide = 0;
    const slider = document.getElementById('trendingSlider');
    const slides = document.querySelectorAll('.trending-slide');
    const totalSlides = slides.length;
    const slidesToShow = window.innerWidth <= 768 ? 1 : window.innerWidth <= 1024 ? 2 : 3;
    const maxSlide = Math.max(0, totalSlides - slidesToShow);

    function updateSlider() {
        if (slider) {
            const slideWidth = slides[0]?.offsetWidth || 320;
            const gap = 24; // 1.5rem gap
            const translateX = -(currentSlide * (slideWidth + gap));
            slider.style.transform = `translateX(${translateX}px)`;
        }
        updateIndicators();
    }

    function slideTrending(direction) {
        if (direction === 'next') {
            currentSlide = currentSlide >= maxSlide ? 0 : currentSlide + 1;
        } else {
            currentSlide = currentSlide <= 0 ? maxSlide : currentSlide - 1;
        }
        updateSlider();
    }

    function updateIndicators() {
        const indicatorsContainer = document.getElementById('trendingIndicators');
        if (!indicatorsContainer) return;
        
        indicatorsContainer.innerHTML = '';
        const indicatorCount = Math.ceil(totalSlides / slidesToShow);
        
        for (let i = 0; i < indicatorCount; i++) {
            const indicator = document.createElement('div');
            indicator.className = `trending-indicator ${i === Math.floor(currentSlide / slidesToShow) ? 'active' : ''}`;
            indicator.onclick = () => {
                currentSlide = i * slidesToShow;
                if (currentSlide > maxSlide) currentSlide = maxSlide;
                updateSlider();
            };
            indicatorsContainer.appendChild(indicator);
        }
    }

    // Auto-slide functionality
    let autoSlideInterval;
    function startAutoSlide() {
        autoSlideInterval = setInterval(() => {
            slideTrending('next');
        }, 5000);
    }

    function stopAutoSlide() {
        clearInterval(autoSlideInterval);
    }

    // Initialize slider
    if (slider && totalSlides > 0) {
        updateSlider();
        startAutoSlide();
        
        // Pause auto-slide on hover
        slider.addEventListener('mouseenter', stopAutoSlide);
        slider.addEventListener('mouseleave', startAutoSlide);
    }

    // Make slideTrending function global
    window.slideTrending = slideTrending;

    // Currency Switching Functionality
    function switchCurrency(currencyCode) {
        fetch('{{ route("currency.switch") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ currency: currencyCode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the page to update all prices
                window.location.reload();
            } else {
                console.error('Currency switch failed:', data.message);
            }
        })
        .catch(error => {
            console.error('Error switching currency:', error);
        });
    }

    // Attach currency switching to dropdown
    document.addEventListener('DOMContentLoaded', function() {
        const currencyDropdown = document.querySelector('#currencyDropdown');
        if (currencyDropdown) {
            currencyDropdown.addEventListener('change', function() {
                switchCurrency(this.value);
            });
        }

        // Also handle currency links if they exist
        document.querySelectorAll('[data-currency]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                switchCurrency(this.dataset.currency);
            });
        });
    });

    // Responsive slider updates
    window.addEventListener('resize', function() {
        const newSlidesToShow = window.innerWidth <= 768 ? 1 : window.innerWidth <= 1024 ? 2 : 3;
        if (newSlidesToShow !== slidesToShow) {
            location.reload(); // Simple solution for responsive changes
        }
    });

    // Add to cart functionality
    function addToCart(productId) {
        // This would typically make an AJAX request to add the product to cart
        console.log('Adding product to cart:', productId);
        
        // Show a success message or update cart UI
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.textContent = '{{ __("app.cart.added_to_cart") }}';
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--voltronix-gradient);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            z-index: 9999;
            animation: slideIn 0.3s ease;
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    // Make addToCart function global
    window.addToCart = addToCart;
</script>

<style>
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.toast-notification {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
</style>
@endpush
