@props([
    'src' => '',
    'alt' => '',
    'title' => '',
    'class' => '',
    'width' => null,
    'height' => null,
    'loading' => 'lazy',
    'product' => null,
    'category' => null
])

@php
    // ✅ SEO-Optimized Image Component with Auto Alt Text Generation
    // Automatically generates descriptive alt text for products and categories
    
    $finalAlt = $alt;
    $finalTitle = $title;
    
    // Auto-generate alt text for products
    if ($product && !$alt) {
        $productName = $product->getTranslation('name');
        $categoryName = $product->category ? $product->category->getTranslation('name') : '';
        
        $finalAlt = $productName;
        if ($categoryName) {
            $finalAlt .= ' - ' . $categoryName . ' ' . __('app.seo.category');
        }
        $finalAlt .= ' | ' . __('app.seo.digital_product');
        
        $finalTitle = $productName;
    }
    
    // Auto-generate alt text for categories
    if ($category && !$alt) {
        $categoryName = $category->getTranslation('name');
        $productCount = $category->products_count ?? $category->products()->count();
        
        $finalAlt = $categoryName . ' ' . __('app.seo.category');
        if ($productCount > 0) {
            $finalAlt .= ' - ' . $productCount . ' ' . __('app.seo.products');
        }
        
        $finalTitle = $categoryName;
    }
    
    // Fallback alt text
    if (!$finalAlt) {
        $finalAlt = __('app.seo.image_alt_fallback');
    }
    
    // Clean up alt text (remove HTML tags, limit length)
    $finalAlt = strip_tags($finalAlt);
    $finalAlt = \Str::limit($finalAlt, 125);
    
    // Build image attributes
    $attributes = [
        'src' => $src,
        'alt' => $finalAlt,
        'class' => $class,
        'loading' => $loading
    ];
    
    if ($finalTitle) {
        $attributes['title'] = strip_tags($finalTitle);
    }
    
    if ($width) {
        $attributes['width'] = $width;
    }
    
    if ($height) {
        $attributes['height'] = $height;
    }
@endphp

<img {{ collect($attributes)->map(fn($value, $key) => $key . '="' . e($value) . '"')->implode(' ') }}>
