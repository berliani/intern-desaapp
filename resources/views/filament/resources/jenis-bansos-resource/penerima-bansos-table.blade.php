<div class="border border-gray-200 rounded-xl">
    <div class="overflow-x-auto">
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[250px]">
                        Nama Penerima
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[180px]">
                        NIK
                    </th>


                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[180px]">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[120px]">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($getState()['penerima'] as $penerima)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            <a href="{{ route('filament.admin.resources.penduduks.view', ['record' => $penerima->penduduk_id]) }}"
                               class="text-primary-600 hover:text-primary-900">
                                {{ $penerima->penduduk->nama ?? 'Penduduk tidak ditemukan' }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $penerima->penduduk->nik ?? 'N/A' }}
                        </td>

                      
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'Diajukan' => 'info',
                                    'Pengajuan' => 'info',
                                    'Diproses' => 'warning',
                                    'Diverifikasi' => 'primary',
                                    'Disetujui' => 'success',
                                    'Ditolak' => 'danger',
                                    'Diterima' => 'success',
                                    'Sudah Diterima' => 'success'
                                ];

                                $icons = [
                                    'Diajukan' => 'heroicon-m-document-text',
                                    'Pengajuan' => 'heroicon-m-document-text',
                                    'Diproses' => 'heroicon-m-clock',
                                    'Diverifikasi' => 'heroicon-m-check-circle',
                                    'Disetujui' => 'heroicon-m-check-badge',
                                    'Ditolak' => 'heroicon-m-x-circle',
                                    'Diterima' => 'heroicon-m-banknotes',
                                    'Sudah Diterima' => 'heroicon-m-banknotes'
                                ];

                                $status = $penerima->status ?? 'Pengajuan';
                                $color = $statusColors[$status] ?? 'gray';
                                $icon = $icons[$status] ?? 'heroicon-m-question-mark-circle';
                            @endphp

                            <x-filament::badge
                                :color="$color"
                                :icon="$icon"
                                class="inline-flex whitespace-normal"
                            >
                                {{ $status }}
                            </x-filament::badge>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('filament.admin.resources.bansos.view', ['record' => $penerima->id]) }}"
                                   class="text-primary-600 hover:text-primary-900 font-medium">
                                    Lihat
                                </a>
                                <a href="{{ route('filament.admin.resources.bansos.edit', ['record' => $penerima->id]) }}"
                                   class="text-warning-600 hover:text-warning-900 font-medium">
                                    Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center justify-center space-y-1 text-gray-500">
                                <div class="flex items-center justify-center h-12 w-12 bg-gray-100 rounded-full mb-2">
                                    <x-heroicon-o-question-mark-circle class="h-6 w-6" />
                                </div>
                                <span class="font-medium">Belum ada penerima bantuan</span>
                                <span class="text-xs">Silakan tambah penerima baru dengan tombol "Tambah Penerima"</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
