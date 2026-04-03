@props([
    'title' => '',
    'description' => '',
    'keywords' => '',
    'image' => '',
    'url' => '',
    'type' => 'website',
    'siteName' => 'Voltronix Digital Store',
    'locale' => '',
    'canonicalUrl' => '',
    'noindex' => false,
    'structuredData' => null
])

@php
    $finalTitle = $title ? $title . ' - ' . $siteName : $siteName;
    $finalDescription = $description ?: __('app.hero.subtitle');
    $finalKeywords = $keywords ?: 'digital store, software, gaming, subscriptions, digital tools, voltronix';
    $finalImage = $image ?: asset('images/logo nt.png');
    $finalUrl = $url ?: url()->current();
    $finalLocale = $locale ?: app()->getLocale();
    $finalCanonical = $canonicalUrl ?: $finalUrl;
@endphp

<!-- SEO Meta Tags -->
<title>{{ $finalTitle }}</title>
<meta name="description" content="{{ $finalDescription }}">
<meta name="keywords" content="{{ $finalKeywords }}">
<meta name="author" content="Voltronix Digital Store">

<!-- Canonical URL -->
<link rel="canonical" href="{{ $finalCanonical }}">

<!-- Robots -->
@if($noindex)
    <meta name="robots" content="noindex, nofollow">
@else
    <meta name="robots" content="index, follow">
@endif

<!-- Open Graph Meta Tags -->
<meta property="og:title" content="{{ $finalTitle }}">
<meta property="og:description" content="{{ $finalDescription }}">
<meta property="og:image" content="{{ $finalImage }}">
<meta property="og:url" content="{{ $finalUrl }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="{{ $finalLocale === 'ar' ? 'ar_SA' : 'en_US' }}">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $finalTitle }}">
<meta name="twitter:description" content="{{ $finalDescription }}">
<meta name="twitter:image" content="{{ $finalImage }}">
<meta name="twitter:site" content="@voltronix">
<meta name="twitter:creator" content="@voltronix">

<!-- Additional Meta Tags -->
<meta name="theme-color" content="#007fff">
<meta name="msapplication-TileColor" content="#007fff">
<meta name="application-name" content="{{ $siteName }}">

<!-- Structured Data -->
@if($structuredData)
    <script type="application/ld+json">
        {!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@else
    <!-- Default Organization Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "{{ $siteName }}",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('images/logo nt.png') }}",
        "description": "{{ __('app.hero.subtitle') }}",
        "contactPoint": {
            "@type": "ContactPoint",
            "contactType": "customer service",
            "availableLanguage": ["English", "Arabic"]
        },
        "sameAs": [
            "https://facebook.com/voltronix",
            "https://twitter.com/voltronix",
            "https://instagram.com/voltronix"
        ]
    }
    </script>
@endif

<!-- Breadcrumb Schema (if on product or category page) -->
@if(request()->routeIs('products.show') || request()->routeIs('categories.show'))
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "{{ __('app.nav.home') }}",
                "item": "{{ url('/') }}"
            }
            @if(request()->routeIs('products.show'))
                ,{
                    "@type": "ListItem",
                    "position": 2,
                    "name": "{{ __('app.nav.products') }}",
                    "item": "{{ route('products.index') }}"
                }
                @if(isset($product))
                    ,{
                        "@type": "ListItem",
                        "position": 3,
                        "name": "{{ $product->getTranslation('name') }}",
                        "item": "{{ route('products.show', $product->slug) }}"
                    }
                @endif
            @elseif(request()->routeIs('categories.show'))
                ,{
                    "@type": "ListItem",
                    "position": 2,
                    "name": "{{ __('app.nav.categories') }}",
                    "item": "{{ route('categories.index') }}"
                }
                @if(isset($category))
                    ,{
                        "@type": "ListItem",
                        "position": 3,
                        "name": "{{ $category->getTranslation('name') }}",
                        "item": "{{ route('categories.show', $category->slug) }}"
                    }
                @endif
            @endif
        ]
    }
    </script>
@endif

<!-- Product Schema (if on product page) -->
@if(request()->routeIs('products.show') && isset($product))
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Product",
        "name": "{{ $product->getTranslation('name') }}",
        "description": "{{ $product->getTranslation('description') }}",
        "image": "{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('images/logo nt.png') }}",
        "brand": {
            "@type": "Brand",
            "name": "{{ $siteName }}"
        },
        "offers": {
            "@type": "Offer",
            "price": "{{ $product->price }}",
            "priceCurrency": "{{ current_currency()->code }}",
            "availability": "{{ $product->isAvailable() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
            "seller": {
                "@type": "Organization",
                "name": "{{ $siteName }}"
            }
        }
        @if($product->reviews_count > 0)
            ,"aggregateRating": {
                "@type": "AggregateRating",
                "ratingValue": "{{ $product->average_rating }}",
                "reviewCount": "{{ $product->reviews_count }}",
                "bestRating": "5",
                "worstRating": "1"
            }
        @endif
    }
    </script>
@endif

<!-- WebSite Schema for search -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "{{ $siteName }}",
    "url": "{{ url('/') }}",
    "potentialAction": {
        "@type": "SearchAction",
        "target": {
            "@type": "EntryPoint",
            "urlTemplate": "{{ route('products.index') }}?search={search_term_string}"
        },
        "query-input": "required name=search_term_string"
    }
}
</script>

<!-- Enhanced Hreflang for multilingual support -->
<x-hreflang-tags />
