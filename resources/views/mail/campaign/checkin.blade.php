<x-mail::message>
# {{ $type }}

The body of your message.

<x-mail::button :url="$url">
References ---
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
