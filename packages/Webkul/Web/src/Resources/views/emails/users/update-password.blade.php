<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('web::mail.update-password.subject') }}</title>
</head>
<body style="font-family: system-ui, sans-serif; line-height: 1.5; color: #1e293b;">
    <p>{{ __('web::mail.update-password.subject') }}</p>
    <p><strong>{{ $user->name ?? $user->email }}</strong></p>
</body>
</html>
