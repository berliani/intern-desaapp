@php
    $anggota = $getState()['anggota'] ?? collect();
@endphp

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr class="bg-gray-50">
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">L/P</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Lahir</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($anggota as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $item->nik }}
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                        {{ $item->nama }}
                        @if($item->kepala_keluarga)
                            <span class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Kepala Keluarga
                            </span>
                        @endif
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                        @if($item->jenis_kelamin === 'L')
                            <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">L</span>
                        @else
                            <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-pink-100 text-pink-800">P</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                        {{ date('d F Y', strtotime($item->tanggal_lahir)) }}
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                        {{ $item->status_perkawinan }}
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                        <a href="{{ route('filament.admin.resources.penduduks.view', ['record' => $item->id]) }}"
                           class="text-primary-600 hover:text-primary-900 px-3">
                            Lihat
                        </a>
                        <a href="{{ route('filament.admin.resources.penduduks.edit', ['record' => $item->id]) }}"
                           class="text-warning-600 hover:text-warning-900">
                            Edit
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>