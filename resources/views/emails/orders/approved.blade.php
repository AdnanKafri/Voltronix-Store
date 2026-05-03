@component('emails.layouts.notification', ['subjectLine' => __('emails.order_approved.subject', ['order_number' => $order->order_number])])
    <h2 style="margin:0 0 12px;">{{ __('emails.order_approved.title') }}</h2>
    <p style="margin:0 0 8px;">{{ __('emails.common.greeting', ['name' => $order->customer_name]) }}</p>
    <p style="margin:0 0 14px;">{{ __('emails.order_approved.message', ['order_number' => $order->order_number]) }}</p>

    @include('emails.orders.partials.order-details', ['order' => $order])

    <p style="margin:14px 0 18px;">{{ __('emails.order_approved.delivery_note') }}</p>

    <a href="{{ route('orders.show', $order->order_number) }}" style="display:inline-block;padding:10px 16px;background:#007fff;color:#fff;text-decoration:none;border-radius:8px;font-weight:700;">
        {{ __('emails.common.view_order') }}
    </a>
@endcomponent

