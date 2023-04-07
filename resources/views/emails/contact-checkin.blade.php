<x-mail::message>
# {{ $type }}

<x-mail::panel>
{{ $message }}
</x-mail::panel>

<x-mail::button :url="$url">
    Reference
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
