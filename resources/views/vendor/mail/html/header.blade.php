@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<div style="font-size: 24px; font-weight: 700; color: #0d6efd; text-decoration: none;">
    <span style="margin-right: 8px;">⚡</span>Voltronix Digital Store
</div>
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
