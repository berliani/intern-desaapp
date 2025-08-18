@php
    $state = $getState();
    $mapQuery = urlencode($state['query'] ?? '');
    $title = $state['title'] ?? 'Lokasi Desa';
    $mapUrl = "https://maps.google.com/maps?q={$mapQuery}&output=embed";
@endphp

<div class="fi-in-component-ctn">
    <div class="mb-2 font-medium">Peta Lokasi {{ $title }}</div>

    <div class="overflow-hidden rounded-lg border border-gray-300 bg-white">
        <iframe
            src="{{ $mapUrl }}"
            width="100%"
            height="450"
            style="border:0; display: block;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>

    <div class="mt-2 text-sm text-gray-500">
        Klik pada peta untuk interaksi lebih lanjut atau membuka di Google Maps.
    </div>
</div>