<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subjectLine ?? config('app.name') }}</title>
</head>
<body style="margin:0;background:#f4f7fb;font-family:Arial,sans-serif;color:#1f2937;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f7fb;padding:24px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="640" cellspacing="0" cellpadding="0" style="max-width:640px;width:100%;background:#ffffff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;">
                <tr>
                    <td style="background:linear-gradient(135deg,#007fff,#23efff);padding:18px 22px;color:#fff;font-weight:700;font-size:18px;">
                        {{ config('app.name') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding:22px;">
                        {{ $slot }}
                    </td>
                </tr>
                <tr>
                    <td style="padding:16px 22px;background:#f8fafc;color:#6b7280;font-size:12px;">
                        © {{ date('Y') }} {{ config('app.name') }}. {{ __('emails.all_rights_reserved') }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

