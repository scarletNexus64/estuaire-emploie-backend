@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@elseif (trim($slot) === 'Estuaire Emploie' || trim($slot) === config('app.name'))
@php
    $logoPath = public_path('images/logo-estuaire-emploi.png');
    $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';
@endphp
<img src="{{ $logoBase64 }}" class="logo" alt="Estuaire Emploi Logo" style="height: 80px; width: auto;">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
