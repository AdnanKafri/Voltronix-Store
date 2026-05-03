<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
    <tr style="background:#f8fafc;">
        <th align="left" style="padding:10px;border-bottom:1px solid #e5e7eb;">{{ __('emails.common.product') }}</th>
        <th align="center" style="padding:10px;border-bottom:1px solid #e5e7eb;">{{ __('emails.common.quantity') }}</th>
        <th align="right" style="padding:10px;border-bottom:1px solid #e5e7eb;">{{ __('emails.common.subtotal') }}</th>
    </tr>
    @foreach($order->items as $item)
        <tr>
            <td style="padding:10px;border-bottom:1px solid #f1f5f9;">{{ $item->getTranslation() }}</td>
            <td align="center" style="padding:10px;border-bottom:1px solid #f1f5f9;">{{ $item->quantity }}</td>
            <td align="right" style="padding:10px;border-bottom:1px solid #f1f5f9;">{{ $order->formatMoney($item->subtotal) }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="2" align="right" style="padding:10px;font-weight:700;">{{ __('emails.common.total') }}</td>
        <td align="right" style="padding:10px;font-weight:700;">{{ $order->formatted_total }}</td>
    </tr>
</table>

