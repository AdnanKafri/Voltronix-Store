@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
    <div style="font-family: 'Poppins', 'Noto Sans Arabic', sans-serif; line-height: 1.6; color: #333;">
        <h2 style="color: #0d6efd;">@lang('emails.order_confirmation.hello', ['name' => $order->customer_name])</h2>
        
        <p>@lang('emails.order_confirmation.thank_you')</p>
        <p>@lang('emails.order_confirmation.order_number', ['number' => $order->order_number])</p>
        
        <div style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 5px;">
            <h3 style="margin-top: 0; color: #0d6efd;">@lang('emails.order_confirmation.order_summary')</h3>
            
            <table style="width: 100%; border-collapse: collapse; margin: 10px 0;">
                <thead>
                    <tr style="background-color: #f1f1f1;">
                        <th style="padding: 10px; text-align: left;">@lang('emails.order_confirmation.product')</th>
                        <th style="padding: 10px; text-align: right;">@lang('emails.order_confirmation.price')</th>
                        <th style="padding: 10px; text-align: center;">@lang('emails.order_confirmation.quantity')</th>
                        <th style="padding: 10px; text-align: right;">@lang('emails.order_confirmation.subtotal')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px;">{{ $item->getTranslation() }}</td>
                            <td style="padding: 10px; text-align: right;">{{ $order->formatMoney($item->product_price) }}</td>
                            <td style="padding: 10px; text-align: center;">{{ $item->quantity }}</td>
                            <td style="padding: 10px; text-align: right;">{{ $order->formatMoney($item->subtotal) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" style="text-align: right; padding: 10px; font-weight: bold;">
                            @lang('emails.order_confirmation.total'):
                        </td>
                        <td style="text-align: right; padding: 10px; font-weight: bold;">
                            {{ $order->formatted_total }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <p>@lang('emails.order_confirmation.payment_method', ['method' => $order->payment_method_name])</p>
        
        @if($order->payment_proof_path)
            <p>@lang('emails.order_confirmation.payment_proof_received')</p>
        @endif
        
        <p>@lang('emails.order_confirmation.status_update', ['status' => $order->localized_status])</p>
        
        <div style="margin: 20px 0; text-align: center;">
            @component('mail::button', ['url' => route('orders.show', $order->order_number)])
                @lang('emails.order_confirmation.view_order')
            @endcomponent
        </div>
        
        <p>@lang('emails.order_confirmation.contact_support')</p>
        
        <p>@lang('emails.order_confirmation.thanks')<br>
        {{ config('app.name') }}</p>
    </div>

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. @lang('emails.all_rights_reserved')
        @endcomponent
    @endslot
@endcomponent
