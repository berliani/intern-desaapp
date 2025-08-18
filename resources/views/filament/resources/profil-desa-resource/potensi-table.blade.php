@php
    $potensi = $getState()['potensi'] ?? [];

    $kategoriLabels = [
        'sda' => 'Sumber Daya Alam',
        'pertanian' => 'Pertanian',
        'peternakan' => 'Peternakan',
        'pariwisata' => 'Pariwisata',
        'industri' => 'Industri/UMKM',
        'budaya' => 'Budaya/Kesenian',
        'lingkungan' => 'Lingkungan',
        'pendidikan' => 'Pendidikan',
        'kesehatan' => 'Kesehatan',
        'lainnya' => 'Lainnya',
    ];

    $kategoriColors = [
        'sda' => 'bg-green-100 text-green-800',
        'pertanian' => 'bg-lime-100 text-lime-800',
        'peternakan' => 'bg-amber-100 text-amber-800',
        'pariwisata' => 'bg-blue-100 text-blue-800',
        'industri' => 'bg-purple-100 text-purple-800',
        'budaya' => 'bg-pink-100 text-pink-800',
        'lingkungan' => 'bg-teal-100 text-teal-800',
        'pendidikan' => 'bg-indigo-100 text-indigo-800',
        'kesehatan' => 'bg-red-100 text-red-800',
        'lainnya' => 'bg-gray-100 text-gray-800',
    ];
@endphp

@if(empty($potensi))
    <div class="text-center py-4 text-gray-500">Belum ada data potensi desa</div>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Potensi</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($potensi as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $item['nama'] ?? '-' }}
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                            @if(isset($item['kategori']))
                                <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full {{ $kategoriColors[$item['kategori']] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $kategoriLabels[$item['kategori']] ?? $item['kategori'] }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                            {{ $item['lokasi'] ?? '-' }}
                        </td>
                        <td class="px-3 py-2 text-sm text-gray-500">
                            {{ $item['deskripsi'] ?? '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif