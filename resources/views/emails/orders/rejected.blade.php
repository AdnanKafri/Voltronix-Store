@component('emails.layouts.notification', ['subjectLine' => __('emails.order_rejected.subject', ['order_number' => $order->order_number])])
    <h2 style="margin:0 0 12px;">{{ __('emails.order_rejected.title') }}</h2>
    <p style="margin:0 0 8px;">{{ __('emails.common.greeting', ['name' => $order->customer_name]) }}</p>
    <p style="margin:0 0 14px;">{{ __('emails.order_rejected.message', ['order_number' => $order->order_number]) }}</p>

    @if(!empty($order->rejection_reason))
        <p style="margin:0 0 14px;"><strong>{{ __('emails.order_rejected.reason') }}:</strong> {{ $order->rejection_reason }}</p>
    @endif

    <a href="{{ route('orders.show', $order->order_number) }}" style="display:inline-block;padding:10px 16px;background:#007fff;color:#fff;text-decoration:none;border-radius:8px;font-weight:700;">
        {{ __('emails.common.view_order') }}
    </a>
@endcomponent

