<x-mail::message>
# Notification

The body of your message. {{ $type }}

<x-mail::button :url="$url">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
