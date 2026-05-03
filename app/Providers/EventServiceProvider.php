<?php

namespace App\Providers;

use App\Events\DeliveryCreated;
use App\Events\OrderPlaced;
use App\Events\OrderStatusChanged;
use App\Listeners\MergeGuestCartOnLogin;
use App\Listeners\ProcessDeliveryAutomation;
use App\Listeners\SendDeliveryReadyNotification;
use App\Listeners\SendOrderPlacedNotifications;
use App\Listeners\SendOrderStatusNotifications;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Login::class => [
            MergeGuestCartOnLogin::class,
        ],
        OrderPlaced::class => [
            SendOrderPlacedNotifications::class,
        ],
        OrderStatusChanged::class => [
            ProcessDeliveryAutomation::class,
            SendOrderStatusNotifications::class,
        ],
        DeliveryCreated::class => [
            SendDeliveryReadyNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
