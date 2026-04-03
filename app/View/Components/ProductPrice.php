<?php

namespace App\View\Components;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductPrice extends Component
{
    public Product $product;
    public string $size;

    /**
     * Create a new component instance.
     */
    public function __construct(Product $product, string $size = 'normal')
    {
        $this->product = $product;
        $this->size = $size;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.product-price');
    }
}
