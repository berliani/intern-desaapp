<div class="border border-gray-200 rounded-xl bg-white overflow-hidden shadow-sm">
    {{-- <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
        <h3 class="text-base font-medium text-gray-900">Dokumen yang Dibutuhkan</h3>
    </div> --}}

    <div class="divide-y divide-gray-200">
        @forelse($getState() ?? [] as $index => $item)
            <div class="p-4 hover:bg-gray-50">
                <div class="flex gap-3 items-start">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary-500 flex items-center justify-center mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-white">
                            <path fill-rule="evenodd" d="M4.5 2A1.5 1.5 0 003 3.5v13A1.5 1.5 0 004.5 18h11a1.5 1.5 0 001.5-1.5V7.621a1.5 1.5 0 00-.44-1.06l-4.12-4.122A1.5 1.5 0 0011.378 2H4.5zm2.25 8.5a.75.75 0 000 1.5h6.5a.75.75 0 000-1.5h-6.5zm0 3a.75.75 0 000 1.5h6.5a.75.75 0 000-1.5h-6.5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">
                            {{ $item['dokumen'] }}
                        </div>

                        @if(!empty($item['keterangan']))
                            <div class="mt-1 text-sm text-gray-600">
                                {{ $item['keterangan'] }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="p-6 text-center">
                <div class="flex flex-col items-center justify-center space-y-1 text-gray-500">
                    <div class="flex items-center justify-center h-12 w-12 bg-gray-100 rounded-full mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                        </svg>
                    </div>
                    <span class="font-medium">Tidak ada persyaratan</span>
                    <span class="text-xs">Tidak ada dokumen khusus yang dibutuhkan untuk layanan ini</span>
                </div>
            </div>
        @endforelse
    </div>
</div>