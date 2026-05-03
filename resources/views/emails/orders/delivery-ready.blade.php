@component('emails.layouts.notification', ['subjectLine' => __('emails.delivery_ready.subject', ['order_number' => $order->order_number])])
    <h2 style="margin:0 0 12px;">{{ __('emails.delivery_ready.title') }}</h2>
    <p style="margin:0 0 8px;">{{ __('emails.common.greeting', ['name' => $order->customer_name]) }}</p>
    <p style="margin:0 0 14px;">{{ __('emails.delivery_ready.message', ['order_number' => $order->order_number]) }}</p>

    <p style="margin:0 0 8px;"><strong>{{ __('emails.delivery_ready.delivery_type') }}:</strong> {{ ucfirst($delivery->type) }}</p>
    <p style="margin:0 0 14px;"><strong>{{ __('emails.delivery_ready.product') }}:</strong> {{ $delivery->orderItem?->getTranslation() }}</p>

    <a href="{{ route('orders.show', $order->order_number) }}" style="display:inline-block;padding:10px 16px;background:#007fff;color:#fff;text-decoration:none;border-radius:8px;font-weight:700;">
        {{ __('emails.common.access_delivery') }}
    </a>
@endcomponent

