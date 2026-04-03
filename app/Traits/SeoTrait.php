<?php

namespace App\Traits;

use Illuminate\Support\Facades\View;

/**
 * ✅ SEO Trait for Dynamic Meta Tag Population
 * Provides easy methods for controllers to set SEO meta data
 */
trait SeoTrait
{
    /**
     * Set SEO meta data for the view
     */
    protected function setSeoData(array $seoData): void
    {
        // Merge with defaults
        $defaults = [
            'title' => config('app.name'),
            'description' => __('app.hero.subtitle'),
            'keywords' => 'digital store, software, gaming, subscriptions, digital tools, voltronix',
            'image' => asset('images/logo nt.png'),
            'type' => 'website',
            'noindex' => false,
        ];

        $finalSeoData = array_merge($defaults, $seoData);

        // Share with all views
        View::share($finalSeoData);
    }

    /**
     * Set page title with automatic site name suffix
     */
    protected function setPageTitle(string $title): void
    {
        $this->setSeoData(['title' => $title]);
    }

    /**
     * Set meta description
     */
    protected function setMetaDescription(string $description): void
    {
        $this->setSeoData(['description' => $description]);
    }

    /**
     * Set meta keywords
     */
    protected function setMetaKeywords(string $keywords): void
    {
        $this->setSeoData(['keywords' => $keywords]);
    }

    /**
     * Set Open Graph image
     */
    protected function setOgImage(string $imageUrl): void
    {
        $this->setSeoData(['image' => $imageUrl]);
    }

    /**
     * Set canonical URL
     */
    protected function setCanonicalUrl(string $url): void
    {
        $this->setSeoData(['canonicalUrl' => $url]);
    }

    /**
     * Set structured data
     */
    protected function setStructuredData(array $structuredData): void
    {
        $this->setSeoData(['structuredData' => $structuredData]);
    }

    /**
     * Mark page as noindex
     */
    protected function setNoIndex(bool $noindex = true): void
    {
        $this->setSeoData(['noindex' => $noindex]);
    }

    /**
     * Generate product SEO data
     */
    protected function generateProductSeo($product): array
    {
        $productName = $product->getTranslation('name');
        $productDescription = $product->getTranslation('description');
        $categoryName = $product->category ? $product->category->getTranslation('name') : '';

        return [
            'title' => $productName,
            'description' => \Str::limit(strip_tags($productDescription), 160),
            'keywords' => implode(', ', array_filter([
                $productName,
                $categoryName,
                'digital product',
                'software',
                'download'
            ])),
            'image' => $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('images/logo nt.png'),
            'type' => 'product',
            'canonicalUrl' => route('products.show', $product->slug),
            'structuredData' => $this->generateProductStructuredData($product)
        ];
    }

    /**
     * Generate category SEO data
     */
    protected function generateCategorySeo($category): array
    {
        $categoryName = $category->getTranslation('name');
        $categoryDescription = $category->getTranslation('description');

        return [
            'title' => $categoryName,
            'description' => \Str::limit(strip_tags($categoryDescription), 160),
            'keywords' => implode(', ', array_filter([
                $categoryName,
                'digital products',
                'software category',
                'downloads'
            ])),
            'image' => $category->thumbnail ? asset('storage/' . $category->thumbnail) : asset('images/logo nt.png'),
            'type' => 'website',
            'canonicalUrl' => route('categories.show', $category->slug)
        ];
    }

    /**
     * Generate product structured data
     */
    private function generateProductStructuredData($product): array
    {
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->getTranslation('name'),
            'description' => \Str::limit(strip_tags($product->getTranslation('description')), 300),
            'image' => $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('images/logo nt.png'),
            'brand' => [
                '@type' => 'Brand',
                'name' => 'Voltronix Digital Store'
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => $product->effective_price,
                'priceCurrency' => 'USD', // TODO: Make dynamic based on current currency
                'availability' => $product->isAvailable() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => 'Voltronix Digital Store'
                ]
            ]
        ];

        // Add rating data if available
        if ($product->reviews_count > 0) {
            $structuredData['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $product->average_rating,
                'reviewCount' => $product->reviews_count,
                'bestRating' => '5',
                'worstRating' => '1'
            ];
        }

        return $structuredData;
    }
}
