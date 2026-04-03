@props([
    'gaId' => null,
    'gtmId' => null
])

@php
    // ✅ Google Analytics 4 & Tag Manager Integration Component
    // Supports both GA4 and GTM with environment-based configuration
    
    $gaId = $gaId ?: config('services.google.analytics_id');
    $gtmId = $gtmId ?: config('services.google.tag_manager_id');
    $isProduction = app()->environment('production');
@endphp

@if($isProduction && ($gaId || $gtmId))
    @if($gtmId)
        {{-- Google Tag Manager --}}
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','{{ $gtmId }}');
        </script>
    @elseif($gaId)
        {{-- Google Analytics 4 --}}
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $gaId }}', {
                // Enhanced measurement
                enhanced_measurement: true,
                // Page title tracking
                page_title: document.title,
                // Custom dimensions
                custom_map: {
                    'custom_dimension_1': 'user_locale',
                    'custom_dimension_2': 'user_type'
                },
                // Set user properties
                user_properties: {
                    user_locale: '{{ app()->getLocale() }}',
                    user_type: '{{ auth()->check() ? "authenticated" : "guest" }}'
                }
            });

            // ✅ Enhanced E-commerce Tracking Functions
            
            // Track product views
            function trackProductView(productId, productName, category, price) {
                gtag('event', 'view_item', {
                    currency: 'USD',
                    value: price,
                    items: [{
                        item_id: productId,
                        item_name: productName,
                        category: category,
                        price: price,
                        quantity: 1
                    }]
                });
            }

            // Track add to cart
            function trackAddToCart(productId, productName, category, price, quantity = 1) {
                gtag('event', 'add_to_cart', {
                    currency: 'USD',
                    value: price * quantity,
                    items: [{
                        item_id: productId,
                        item_name: productName,
                        category: category,
                        price: price,
                        quantity: quantity
                    }]
                });
            }

            // Track remove from cart
            function trackRemoveFromCart(productId, productName, category, price, quantity = 1) {
                gtag('event', 'remove_from_cart', {
                    currency: 'USD',
                    value: price * quantity,
                    items: [{
                        item_id: productId,
                        item_name: productName,
                        category: category,
                        price: price,
                        quantity: quantity
                    }]
                });
            }

            // Track purchase
            function trackPurchase(transactionId, value, items) {
                gtag('event', 'purchase', {
                    transaction_id: transactionId,
                    value: value,
                    currency: 'USD',
                    items: items
                });
            }

            // Track search
            function trackSearch(searchTerm, resultsCount = null) {
                gtag('event', 'search', {
                    search_term: searchTerm,
                    ...(resultsCount !== null && { search_results_count: resultsCount })
                });
            }

            // Track page views for SPA-like behavior
            function trackPageView(pageTitle, pagePath) {
                gtag('config', '{{ $gaId }}', {
                    page_title: pageTitle,
                    page_location: window.location.origin + pagePath
                });
            }

            // ✅ Automatic Event Tracking
            
            // Track outbound links
            document.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (link && link.hostname !== window.location.hostname) {
                    gtag('event', 'click', {
                        event_category: 'outbound',
                        event_label: link.href,
                        transport_type: 'beacon'
                    });
                }
            });

            // Track file downloads
            document.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (link && link.href.match(/\.(pdf|doc|docx|xls|xlsx|ppt|pptx|zip|rar|7z|exe|dmg)$/i)) {
                    gtag('event', 'file_download', {
                        event_category: 'engagement',
                        event_label: link.href,
                        transport_type: 'beacon'
                    });
                }
            });

            // Track scroll depth
            let scrollDepthTracked = [];
            window.addEventListener('scroll', function() {
                const scrollPercent = Math.round((window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100);
                const milestones = [25, 50, 75, 90];
                
                milestones.forEach(milestone => {
                    if (scrollPercent >= milestone && !scrollDepthTracked.includes(milestone)) {
                        scrollDepthTracked.push(milestone);
                        gtag('event', 'scroll', {
                            event_category: 'engagement',
                            event_label: milestone + '%',
                            value: milestone
                        });
                    }
                });
            });
        </script>
    @endif

    {{-- Google Tag Manager (noscript) --}}
    @if($gtmId)
        <noscript>
            <iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" 
                    height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
    @endif

    {{-- Google Search Console Verification --}}
    @if(config('services.google.search_console_verification'))
        <meta name="google-site-verification" content="{{ config('services.google.search_console_verification') }}">
    @endif
@else
    {{-- Development Mode Notice --}}
    @if(!$isProduction)
        <!-- Google Analytics disabled in {{ app()->environment() }} environment -->
    @endif
@endif
