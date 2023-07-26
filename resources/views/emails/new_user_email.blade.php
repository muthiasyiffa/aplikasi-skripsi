<x-mail::message>
Hi {{ $user->name }},

<p>Your account has been successfully created by Admin. You can now log in using the following link:</p>
<x-mail::button :url="'https://misso.site/login'">
Log in here
</x-mail::button>

<p>Don't forget to change your account password</p>
Regards,<br>
{{ config('app.name') }}
</x-mail::message>