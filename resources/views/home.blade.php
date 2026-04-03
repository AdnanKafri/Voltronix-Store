@extends('layouts.app')

@section('title', __('app.hero.title') . ' - ' . __('app.hero.subtitle'))
@section('description', __('app.footer.description'))

@push('styles')
<style>
    /* ========================================
       VOLTRONIX ELECTRIC THEME - OPTIMIZED
       ======================================== */
    

    
    /* ========================================
       HERO SLIDER
       ======================================== */
    
    /* Hero Slider - Enhanced */
    .hero-slider {
        height: 100vh;
        min-height: 600px;
        position: relative;
        overflow: hidden;
        margin-top: 0;
        background: linear-gradient(180deg, #0d1421 0%, #121b2d 100%);
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
        transition: opacity 1.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .slide.active {
        opacity: 1;
    }
    
    .slide-1 {
        background: linear-gradient(135deg, #0d1421 0%, #1a1a2e 50%, #0d1421 100%);
    }
    
    .slide-2 {
        background: linear-gradient(135deg, #1a1a2e 0%, #1e3a8a 50%, #1a1a2e 100%);
    }
    
    .slide-3 {
        background: linear-gradient(135deg, #0f172a 0%, #1a1a2e 50%, #0f172a 100%);
    }
    
    .slide::before {
        content: '';
        position: absolute;
        inset: 0;
        background: 
            radial-gradient(circle at 30% 40%, rgba(0, 127, 255, 0.25) 0%, transparent 50%),
            radial-gradient(circle at 70% 60%, rgba(35, 239, 255, 0.15) 0%, transparent 50%);
        animation: slideGlow 8s ease-in-out infinite alternate;
    }
    
    @keyframes slideGlow {
        0%, 100% { opacity: 0.6; }
        50% { opacity: 1; }
    }
    
    .slide-content {
        text-align: center;
        color: white;
        z-index: 3;
        position: relative;
        max-width: 900px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .slide-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 4rem;
        font-weight: 900;
        margin-bottom: 1.5rem;
        text-shadow: 0 0 30px rgba(0, 127, 255, 0.6);
        letter-spacing: 2px;
        line-height: 1.1;
    }
    
    .slide-subtitle {
        font-size: 1.4rem;
        margin-bottom: 2.5rem;
        opacity: 0.95;
        line-height: 1.6;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
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
        bottom: 2.5rem;
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
        background: rgba(255, 255, 255, 0.4);
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .slider-dot.active {
        background: var(--voltronix-primary);
        border-color: white;
        transform: scale(1.3);
        box-shadow: 0 0 15px rgba(0, 127, 255, 0.8);
    }
    
    .slider-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(0, 127, 255, 0.3);
        color: white;
        width: 55px;
        height: 55px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        z-index: 20;
        font-size: 1.3rem;
    }
    
    .slider-nav:hover {
        background: var(--voltronix-primary);
        border-color: var(--voltronix-secondary);
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 0 25px rgba(0, 127, 255, 0.6);
    }
    
    .slider-prev { left: 2rem; }
    .slider-next { right: 2rem; }
    
    /* Section Headers - Unified */
    .section-header {
        margin-bottom: 4rem;
    }
    
    .section-title-modern {
        font-family: 'Orbitron', sans-serif;
        font-size: 3rem;
        font-weight: 900;
        color: var(--voltronix-accent);
        margin-bottom: 1rem;
        position: relative;
        display: inline-block;
    }
    
    .section-subtitle-modern {
        font-size: 1.2rem;
        color: #666;
        max-width: 700px;
        margin: 0 auto 2rem;
        line-height: 1.6;
    }

    [dir="rtl"] .slide-title,
    [dir="rtl"] .section-title-modern,
    [dir="rtl"] .category-card-title,
    [dir="rtl"] .product-card-title,
    [dir="rtl"] .feature-title-modern {
        font-family: 'Tajawal', 'Noto Sans Arabic', sans-serif;
        letter-spacing: 0;
        text-transform: none;
        font-feature-settings: "liga" 1, "calt" 1;
    }

    [dir="rtl"] .slide-title,
    [dir="rtl"] .section-title-modern {
        font-weight: 800;
        line-height: 1.2;
    }

    [dir="rtl"] .category-card-title,
    [dir="rtl"] .product-card-title,
    [dir="rtl"] .feature-title-modern {
        font-weight: 700;
        line-height: 1.35;
    }
    
    .section-divider {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .divider-line {
        width: 60px;
        height: 2px;
        background: var(--voltronix-gradient);
    }
    
    .divider-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--voltronix-primary);
        box-shadow: 0 0 15px rgba(0, 127, 255, 0.6);
    }
    
    /* Product Tile Card - Brand New Design */
    .product-tile-card {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 1px 8px rgba(0, 0, 0, 0.06);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        height: 320px;
        border: 1px solid rgba(0, 0, 0, 0.04);
    }
    
    .product-tile-card:hover {
        box-shadow: 0 8px 32px rgba(0, 127, 255, 0.15);
        transform: translateY(-4px);
        border-color: rgba(0, 127, 255, 0.1);
    }
    
    .tile-card-link {
        display: flex;
        flex-direction: column;
        height: 100%;
        text-decoration: none;
        color: inherit;
    }
    
    /* Large Image Area - 70% */
    .tile-image-wrapper {
        position: relative;
        height: 70%;
        overflow: hidden;
        background: linear-gradient(135deg, #f5f7fa, #e8eef5);
    }
    
    .tile-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .product-tile-card:hover .tile-image {
        transform: scale(1.12);
    }
    
    .tile-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: rgba(0, 127, 255, 0.2);
        background: linear-gradient(135deg, rgba(0, 127, 255, 0.03), rgba(35, 239, 255, 0.03));
    }
    
    /* Badge - Top Right */
    .tile-badge {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        z-index: 10;
        backdrop-filter: blur(12px);
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
    }
    
    .tile-badge.discount {
        background: rgba(220, 53, 69, 0.95);
        color: white;
    }
    
    .tile-badge.new {
        background: rgba(40, 167, 69, 0.95);
        color: white;
    }
    
    .tile-badge.featured {
        background: rgba(255, 193, 7, 0.95);
        color: #000;
    }
    
    /* Quick View Icon - Center on Hover */
    .tile-quick-view {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(0, 127, 255, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        opacity: 0;
        transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        z-index: 5;
        box-shadow: 0 8px 24px rgba(0, 127, 255, 0.4);
    }
    
    .product-tile-card:hover .tile-quick-view {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
    }
    
    /* Floating Info Bar - 30% */
    .tile-info-bar {
        height: 30%;
        padding: 0.85rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        background: white;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
        position: relative;
    }
    
    .tile-info-bar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--voltronix-primary), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .product-tile-card:hover .tile-info-bar::before {
        opacity: 1;
    }
    
    .tile-content {
        flex: 1;
        min-width: 0;
    }
    
    .tile-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0 0 0.4rem 0;
        line-height: 1.3;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        transition: color 0.25s ease;
    }
    
    .product-tile-card:hover .tile-title {
        color: var(--voltronix-primary);
    }
    
    .tile-price-row {
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
    }
    
    .tile-price-old {
        font-size: 0.75rem;
        color: #999;
        text-decoration: line-through;
    }
    
    .tile-price {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--voltronix-primary);
        letter-spacing: -0.3px;
    }
    
    /* Cart Button - Circular */
    .tile-cart-btn {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--voltronix-primary), var(--voltronix-secondary));
        border: none;
        color: white;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 4px 12px rgba(0, 127, 255, 0.25);
        flex-shrink: 0;
    }
    
    .tile-cart-btn:hover {
        transform: scale(1.15) rotate(12deg);
        box-shadow: 0 6px 20px rgba(0, 127, 255, 0.4);
    }
    
    .tile-cart-btn:active {
        transform: scale(1.05);
    }
    
    /* Categories Section - Refined */
    .categories-section {
        background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 50%, #ffffff 100%);
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
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, rgba(0, 127, 255, 0.1) 50%, transparent 100%);
    }
    
    .categories-section::after {
        content: '';
        position: absolute;
        inset: 0;
        background: 
            radial-gradient(circle at 15% 25%, rgba(0, 127, 255, 0.02) 0%, transparent 50%),
            radial-gradient(circle at 85% 75%, rgba(35, 239, 255, 0.02) 0%, transparent 50%);
        pointer-events: none;
    }
    
    /* Featured Products Section */
    .featured-products-section {
        background: linear-gradient(180deg, #ffffff 0%, #fafbfc 100%);
        padding: 6rem 0;
        position: relative;
    }
    
    .featured-products-section::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, rgba(0, 127, 255, 0.08) 50%, transparent 100%);
    }
    
    /* Special Offers Section */
    .special-offers-section {
        background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 50%, #f8f9fa 100%);
        padding: 6rem 0;
        position: relative;
    }
    
    .special-offers-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, rgba(0, 127, 255, 0.1) 50%, transparent 100%);
    }
    
    .special-offers-section::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, rgba(0, 127, 255, 0.1) 50%, transparent 100%);
    }
    
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        max-width: 1320px;
        margin: 0 auto;
    }
    
    .category-card-modern {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0, 127, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        height: 100%;
        display: flex;
        flex-direction: column;
        text-decoration: none;
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
        transition: transform 0.4s ease;
        z-index: 10;
    }
    
    .category-card-modern:hover {
        transform: translateY(-10px);
        border-color: rgba(0, 127, 255, 0.2);
        box-shadow: 0 16px 48px rgba(0, 127, 255, 0.12);
    }
    
    .category-card-modern:hover::before {
        transform: scaleX(1);
    }
    
    .category-image-wrapper {
        position: relative;
        width: 100%;
        height: 200px;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(0, 127, 255, 0.05), rgba(35, 239, 255, 0.05));
    }
    
    .category-card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    
    .category-card-modern:hover .category-card-image {
        transform: scale(1.1);
    }
    
    .category-icon-wrapper {
        width: 100%;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(0, 127, 255, 0.08), rgba(35, 239, 255, 0.08));
    }
    
    .category-icon-large {
        font-size: 4rem;
        color: var(--voltronix-primary);
        opacity: 0.9;
        filter: drop-shadow(0 4px 16px rgba(0, 127, 255, 0.25));
        transition: all 0.4s ease;
    }
    
    .category-card-modern:hover .category-icon-large {
        transform: scale(1.15) rotate(5deg);
        opacity: 1;
    }
    
    .category-card-content {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .category-card-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--voltronix-accent);
        margin-bottom: 0.75rem;
        transition: color 0.3s ease;
    }
    
    .category-card-modern:hover .category-card-title {
        color: var(--voltronix-primary);
    }
    
    .category-card-description {
        color: #6c757d;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1.25rem;
        flex: 1;
    }
    
    .category-card-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 1rem;
        border-top: 1px solid rgba(0, 127, 255, 0.08);
    }
    
    .category-product-count {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(0, 127, 255, 0.08);
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--voltronix-primary);
        transition: all 0.3s ease;
    }
    
    .category-card-modern:hover .category-product-count {
        background: rgba(0, 127, 255, 0.15);
        transform: scale(1.05);
    }
    
    .category-card-arrow {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--voltronix-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 14px rgba(0, 127, 255, 0.2);
    }
    
    .category-card-modern:hover .category-card-arrow {
        transform: translateX(5px) scale(1.1);
        box-shadow: 0 6px 20px rgba(0, 127, 255, 0.4);
    }
    
    /* Why Voltronix Section */
    .why-voltronix-section {
        background: linear-gradient(180deg, #ffffff 0%, #fafbfc 100%);
        padding: 6rem 0;
        position: relative;
    }
    
    .why-voltronix-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, rgba(0, 127, 255, 0.08) 50%, transparent 100%);
    }
    
    .why-voltronix-section::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, rgba(0, 127, 255, 0.08) 50%, transparent 100%);
    }
    
    .feature-card-modern {
        background: linear-gradient(145deg, #ffffff, #f8f9fa);
        border-radius: 20px;
        padding: 2.5rem 2rem;
        text-align: center;
        height: 100%;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
        transition: all 0.4s ease;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }
    
    .feature-card-modern::before {
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
    
    .feature-card-modern:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 127, 255, 0.15);
        border-color: var(--voltronix-primary);
    }
    
    .feature-card-modern:hover::before {
        transform: scaleX(1);
    }
    
    .feature-icon-modern {
        width: 90px;
        height: 90px;
        background: var(--voltronix-gradient);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2.5rem;
        color: white;
        box-shadow: 0 10px 30px rgba(0, 127, 255, 0.3);
        transition: all 0.4s ease;
    }
    
    .feature-card-modern:hover .feature-icon-modern {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 15px 40px rgba(0, 127, 255, 0.4);
    }
    
    .feature-title-modern {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--voltronix-accent);
        margin-bottom: 1rem;
    }
    
    .feature-description-modern {
        color: #666;
        line-height: 1.6;
        font-size: 0.95rem;
    }
    
    /* Stats Section */
    .stats-section {
        background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%);
        color: white;
        padding: 5rem 0;
        position: relative;
        overflow: hidden;
    }
    
    .stats-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent 0%, rgba(0, 127, 255, 0.4) 50%, transparent 100%);
    }
    
    .stats-section::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 30% 50%, rgba(0, 127, 255, 0.12) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite alternate;
        pointer-events: none;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 0.6; }
        50% { opacity: 1; }
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
        box-shadow: 0 10px 30px rgba(0, 127, 255, 0.3);
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
    
    /* Testimonials Section */
    .testimonials-section {
        background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 50%, #f8f9fa 100%);
        padding: 6rem 0;
        position: relative;
    }
    
    .testimonials-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, rgba(0, 127, 255, 0.1) 50%, transparent 100%);
    }
    
    .testimonial-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        height: 100%;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
        border: 2px solid transparent;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }
    
    .testimonial-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--voltronix-gradient);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }
    
    .testimonial-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 127, 255, 0.12);
        border-color: var(--voltronix-primary);
    }
    
    .testimonial-card:hover::before {
        transform: scaleX(1);
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
        color: #ffc107;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }
    
    /* Standardized Section Spacing */
    section {
        transition: background 0.3s ease;
    }
    
    section + section {
        margin-top: 0;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-slider {
            min-height: 500px;
            padding-top: 0;
        }
        
        .slide-title {
            font-size: 2.5rem;
        }
        
        .slide-subtitle {
            font-size: 1.1rem;
        }
        
        .section-title-modern {
            font-size: 2.2rem;
        }
        
        .categories-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .slider-nav {
            width: 45px;
            height: 45px;
        }
        
        .slider-prev { left: 1rem; }
        .slider-next { right: 1rem; }
        
        .stat-number {
            font-size: 2.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .slide-title {
            font-size: 2rem;
        }
        
        .slide-buttons {
            flex-direction: column;
            width: 100%;
        }
        
        .slide-buttons .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
{{-- ========================================
     SECTION 1: HERO SLIDER
     ======================================== --}}
<section class="hero-slider">
    <div class="slider-container">
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
            <div class="slide slide-1 active">
                <div class="slide-content">
                    <h1 class="slide-title">{{ __('app.hero.welcome_title') }}</h1>
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
            
            <div class="slide slide-2">
                <div class="slide-content">
                    <h1 class="slide-title">{{ __('app.hero.quality_title') }}</h1>
                    <p class="slide-subtitle">{{ __('app.hero.quality_subtitle') }}</p>
                    <div class="slide-buttons">
                        <a href="{{ route('products.index') }}" class="btn btn-voltronix-primary">
                            <i class="bi bi-star {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.hero.view_products') }}
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="slide slide-3">
                <div class="slide-content">
                    <h1 class="slide-title">{{ __('app.hero.secure_title') }}</h1>
                    <p class="slide-subtitle">{{ __('app.hero.secure_subtitle') }}</p>
                    <div class="slide-buttons">
                        <a href="{{ route('offers.index') }}" class="btn btn-voltronix-primary">
                            <i class="bi bi-lightning {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.hero.special_offers') }}
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    
    <div class="slider-nav slider-prev" onclick="changeSlide(-1)">
        <i class="bi bi-chevron-left"></i>
    </div>
    <div class="slider-nav slider-next" onclick="changeSlide(1)">
        <i class="bi bi-chevron-right"></i>
    </div>
    
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

{{-- ========================================
     SECTION 2: FEATURED PRODUCTS
     ======================================== --}}
<section class="featured-products-section" style="background: white; padding: 6rem 0;">
    <div class="volt-container">
        <x-section-header 
            :title="__('app.products.featured_title')"
            :subtitle="__('app.products.featured_subtitle')"
            icon="star-fill"
        />
        
        <div class="row g-4">
            @forelse($latestProducts->take(6) as $product)
                <div class="col-lg-4 col-md-6">
                    <x-product-card-compact :product="$product" />
                </div>
            @empty
                @for($i = 1; $i <= 3; $i++)
                    <div class="col-lg-4 col-md-6">
                        <div class="product-card-compact">
                            <div class="product-card-image-container">
                                <div class="product-card-placeholder">
                                    <i class="bi bi-{{ ['laptop', 'controller', 'tools'][$i-1] }}"></i>
                                </div>
                                <div class="product-badge-compact {{ ['new', 'sale', 'featured'][$i-1] }}">
                                    <i class="bi bi-{{ ['star-fill', 'lightning-fill', 'award-fill'][$i-1] }}"></i>
                                    {{ __('app.common.' . ['new', 'sale', 'featured'][$i-1]) }}
                                </div>
                            </div>
                            <div class="product-card-body">
                                <div class="product-category-tag">
                                    <i class="bi bi-tag-fill"></i>
                                    {{ __('app.categories.software.title') }}
                                </div>
                                <h4 class="product-card-title">
                                    <a href="{{ route('products.index') }}">{{ __('app.products.sample_software') }}</a>
                                </h4>
                                <p class="product-card-description">{{ __('app.products.sample_software_desc') }}</p>
                                <div class="product-card-pricing">
                                    <span class="price-current">{{ currency_format(99.99) }}</span>
                                </div>
                                <a href="{{ route('products.index') }}" class="btn-product-view">
                                    <i class="bi bi-arrow-right"></i>
                                    {{ __('app.products.view_details') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endfor
            @endforelse
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('products.index') }}" class="btn btn-voltronix-secondary btn-lg">
                <i class="bi bi-grid {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('app.products.view_all') }}
            </a>
        </div>
    </div>
</section>

{{-- ========================================
     SECTION 3: CATEGORIES
     ======================================== --}}
<section id="categories" class="categories-section">
    <div class="volt-container">
        <x-section-header 
            :title="__('app.categories.title')"
            :subtitle="__('app.categories.subtitle')"
            icon="grid-3x3-gap"
        />
        
        <div class="categories-grid">
            @forelse($categories as $category)
                <a href="{{ route('categories.show', $category->slug) }}" class="category-card-modern">
                    @if($category->thumbnail)
                        <div class="category-image-wrapper">
                            <img class="category-card-image" 
                                 src="{{ asset('storage/' . $category->thumbnail) }}" 
                                 alt="{{ $category->getTranslation('name', app()->getLocale()) }}"
                                 loading="lazy">
                        </div>
                    @else
                        <div class="category-icon-wrapper">
                            <i class="bi bi-grid-3x3-gap category-icon-large"></i>
                        </div>
                    @endif
                    
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
                @foreach([
                    ['icon' => 'laptop', 'key' => 'software', 'count' => 24],
                    ['icon' => 'controller', 'key' => 'gaming', 'count' => 18],
                    ['icon' => 'tools', 'key' => 'tools', 'count' => 32],
                    ['icon' => 'shield-check', 'key' => 'security', 'count' => 15],
                    ['icon' => 'camera-video', 'key' => 'streaming', 'count' => 12],
                    ['icon' => 'mortarboard', 'key' => 'education', 'count' => 28]
                ] as $cat)
                    <a href="{{ route('categories.index') }}" class="category-card-modern">
                        <div class="category-icon-wrapper">
                            <i class="bi bi-{{ $cat['icon'] }} category-icon-large"></i>
                        </div>
                        <div class="category-card-content">
                            <h3 class="category-card-title">{{ __('app.categories.' . $cat['key'] . '.title') }}</h3>
                            <p class="category-card-description">{{ __('app.categories.' . $cat['key'] . '.description') }}</p>
                            <div class="category-card-meta">
                                <span class="category-product-count">
                                    <i class="bi bi-box-seam"></i>
                                    {{ $cat['count'] }} {{ __('app.categories.products_count') }}
                                </span>
                                <span class="category-card-arrow">
                                    <i class="bi bi-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            @endforelse
        </div>
    </div>
</section>

{{-- ========================================
     SECTION 4: SPECIAL OFFERS
     ======================================== --}}
<section class="special-offers-section" style="background: var(--voltronix-light); padding: 6rem 0;">
    <div class="volt-container">
        <x-section-header 
            :title="__('app.offers.title')"
            :subtitle="__('app.offers.subtitle')"
            icon="lightning-charge-fill"
        />
        
        <div class="row g-4">
            @forelse($specialOffers->take(3) as $product)
                <div class="col-lg-4 col-md-6">
                    <x-product-card-compact :product="$product" />
                </div>
            @empty
                @for($i = 1; $i <= 3; $i++)
                    <div class="col-lg-4 col-md-6">
                        <div class="product-card-compact">
                            <div class="product-card-image-container">
                                <div class="product-card-placeholder">
                                    <i class="bi bi-gift"></i>
                                </div>
                                <div class="product-badge-compact sale">
                                    <i class="bi bi-lightning-fill"></i>
                                    {{ 20 + ($i * 5) }}%
                                </div>
                            </div>
                            <div class="product-card-body">
                                <h4 class="product-card-title">
                                    <a href="{{ route('offers.index') }}">Special Offer Product {{ $i }}</a>
                                </h4>
                                <p class="product-card-description">Limited time offer with amazing discount!</p>
                                <div class="product-card-pricing">
                                    <div class="price-group">
                                        <span class="price-original">{{ currency_format(99.99) }}</span>
                                        <span class="price-current">{{ currency_format(79.99 - ($i * 5)) }}</span>
                                    </div>
                                    <span class="price-save">{{ __('app.common.save') }} {{ currency_format(20 + ($i * 5)) }}</span>
                                </div>
                                <a href="{{ route('offers.index') }}" class="btn-product-view">
                                    <i class="bi bi-arrow-right"></i>
                                    {{ __('app.products.view_details') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endfor
            @endforelse
        </div>
    </div>
</section>

{{-- ========================================
     SECTION 5: WHY VOLTRONIX
     ======================================== --}}
<section class="why-voltronix-section">
    <div class="volt-container">
        <x-section-header 
            :title="__('app.features.why_title')"
            :subtitle="__('app.features.why_subtitle')"
            icon="award-fill"
        />
        
        <div class="row g-4">
            @foreach([
                ['icon' => 'shield-check', 'title' => __('app.features.secure.title'), 'desc' => __('app.features.secure.description')],
                ['icon' => 'lightning-charge', 'title' => __('app.features.fast.title'), 'desc' => __('app.features.fast.description')],
                ['icon' => 'headset', 'title' => __('app.features.support.title'), 'desc' => __('app.features.support.description')],
                ['icon' => 'star', 'title' => __('app.features.quality.title'), 'desc' => __('app.features.quality.description')]
            ] as $feature)
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card-modern">
                        <div class="feature-icon-modern">
                            <i class="bi bi-{{ $feature['icon'] }}"></i>
                        </div>
                        <h4 class="feature-title-modern">{{ $feature['title'] }}</h4>
                        <p class="feature-description-modern">{{ $feature['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================
     SECTION 6: STATS
     ======================================== --}}
<section class="stats-section">
    <div class="volt-container">
        <div class="row g-4">
            @foreach([
                ['number' => '10K+', 'label' => __('app.stats.customers')],
                ['number' => '500+', 'label' => __('app.stats.products')],
                ['number' => '99.9%', 'label' => __('app.stats.satisfaction')],
                ['number' => '24/7', 'label' => __('app.stats.support')]
            ] as $stat)
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <span class="stat-number">{{ $stat['number'] }}</span>
                        <span class="stat-label">{{ $stat['label'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================
     SECTION 7: TESTIMONIALS
     ======================================== --}}
<section class="testimonials-section">
    <div class="volt-container">
        <x-section-header 
            :title="__('app.testimonials.title')"
            :subtitle="__('app.testimonials.subtitle')"
            icon="chat-quote-fill"
        />
        
        <div class="row g-4">
            @for($i = 1; $i <= 3; $i++)
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <div class="testimonial-rating">
                            @for($j = 0; $j < 5; $j++)
                                <i class="bi bi-star-fill"></i>
                            @endfor
                        </div>
                        <p class="testimonial-text">
                            "{{ __('app.testimonials.sample_text_' . $i) }}"
                        </p>
                        <div class="testimonial-author">
                            <strong>{{ __('app.testimonials.sample_author_' . $i) }}</strong>
                            <div class="text-muted small">{{ __('app.testimonials.sample_role_' . $i) }}</div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Hero Slider Functionality
    let currentSlideIndex = 0;
    let autoSlideInterval;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.slider-dot');
    const totalSlides = slides.length;

    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
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
        autoSlideInterval = setInterval(nextSlide, 5000);
    }

    function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        startAutoSlide();
    }

    // Initialize slider
    document.addEventListener('DOMContentLoaded', function() {
        showSlide(0);
        startAutoSlide();
        
        const sliderContainer = document.querySelector('.hero-slider');
        sliderContainer.addEventListener('mouseenter', () => clearInterval(autoSlideInterval));
        sliderContainer.addEventListener('mouseleave', () => startAutoSlide());
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') changeSlide(-1);
        else if (e.key === 'ArrowRight') changeSlide(1);
    });

    // Scroll animations
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

    document.querySelectorAll('.product-card-compact, .category-card-modern, .feature-card-modern').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });

    // Stats counter animation
    const statsObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumbers = entry.target.querySelectorAll('.stat-number');
                statNumbers.forEach((stat, index) => {
                    const text = stat.textContent;
                    stat.textContent = '0';
                    setTimeout(() => {
                        stat.textContent = text;
                    }, index * 100);
                });
                statsObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
        statsObserver.observe(statsSection);
    }

    // Add to cart function
    window.addToCart = function(productId) {
        console.log('Adding product to cart:', productId);
        
        const toast = document.createElement('div');
        toast.textContent = '{{ __("app.cart.added_to_cart") }}';
        toast.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            background: var(--voltronix-gradient);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            z-index: 9999;
            box-shadow: 0 8px 25px rgba(0, 127, 255, 0.4);
            animation: slideIn 0.3s ease;
        `;
        
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    };
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
</style>
@endpush
