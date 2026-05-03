@component('emails.layouts.notification', ['subjectLine' => __('emails.order_placed.subject', ['order_number' => $order->order_number])])
    <h2 style="margin:0 0 12px;">{{ __('emails.order_placed.title') }}</h2>
    <p style="margin:0 0 8px;">{{ __('emails.common.greeting', ['name' => $order->customer_name]) }}</p>
    <p style="margin:0 0 14px;">{{ __('emails.order_placed.message', ['order_number' => $order->order_number]) }}</p>

    @include('emails.orders.partials.order-details', ['order' => $order])

    <p style="margin:14px 0 10px;">{{ __('emails.common.payment_method', ['method' => $order->payment_method_name]) }}</p>
    <p style="margin:0 0 18px;">{{ __('emails.order_placed.next_step') }}</p>

    <a href="{{ route('orders.show', $order->order_number) }}" style="display:inline-block;padding:10px 16px;background:#007fff;color:#fff;text-decoration:none;border-radius:8px;font-weight:700;">
        {{ __('emails.common.view_order') }}
    </a>
@endcomponent

