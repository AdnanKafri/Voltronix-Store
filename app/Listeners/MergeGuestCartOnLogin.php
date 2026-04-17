<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\CartService;
use Illuminate\Auth\Events\Login;

class MergeGuestCartOnLogin
{
    public function __construct(
        private readonly CartService $cartService
    ) {
    }

    /**
     * Merge any guest cart into the authenticated storefront user cart.
     */
    public function handle(Login $event): void
    {
        if ($event->guard !== 'web' || ! $event->user instanceof User) {
            return;
        }

        $this->cartService->transferGuestCartToUser($event->user->id);
    }
}
