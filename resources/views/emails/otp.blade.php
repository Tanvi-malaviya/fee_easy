<x-mail::message>
# Verification Code

Hello,

Thank you for registering with **Tuoora**. Please use the following code to verify your account and access your dashboard:

<x-mail::panel>
# {{ $otp }}
</x-mail::panel>

This code will expire in 10 minutes. If you did not request this code, please ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
