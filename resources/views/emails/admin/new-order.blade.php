@component('emails.layouts.notification', ['subjectLine' => __('emails.admin_new_order.subject', ['order_number' => $order->order_number])])
    <h2 style="margin:0 0 12px;">{{ __('emails.admin_new_order.title') }}</h2>
    <p style="margin:0 0 8px;">{{ __('emails.admin_new_order.message', ['order_number' => $order->order_number]) }}</p>

    <p style="margin:0 0 8px;"><strong>{{ __('emails.admin_new_order.customer') }}:</strong> {{ $order->customer_name }} ({{ $order->customer_email }})</p>
    <p style="margin:0 0 8px;"><strong>{{ __('emails.admin_new_order.total') }}:</strong> {{ $order->formatted_total }}</p>
    <p style="margin:0 0 14px;"><strong>{{ __('emails.admin_new_order.payment_method') }}:</strong> {{ $order->payment_method_name }}</p>

    <a href="{{ route('admin.orders.show', $order->order_number) }}" style="display:inline-block;padding:10px 16px;background:#007fff;color:#fff;text-decoration:none;border-radius:8px;font-weight:700;">
        {{ __('emails.admin_new_order.review_order') }}
    </a>
@endcomponent

