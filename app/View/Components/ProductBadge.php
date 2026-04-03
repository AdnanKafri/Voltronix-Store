<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductBadge extends Component
{
    public string $type;
    public string $text;
    public ?int $discount;

    /**
     * Create a new component instance.
     */
    public function __construct(string $type, string $text = '', ?int $discount = null)
    {
        $this->type = $type;
        $this->text = $text;
        $this->discount = $discount;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.product-badge');
    }

    /**
     * Get badge classes based on type
     */
    public function getBadgeClasses(): string
    {
        return match($this->type) {
            'new' => 'badge-new',
            'featured' => 'badge-featured',
            'sale' => 'badge-sale',
            default => 'badge-default'
        };
    }
}
