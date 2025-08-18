<div class="border border-gray-200 rounded-xl bg-white overflow-hidden shadow-sm">
    {{-- <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
        <h3 class="text-base font-medium text-gray-900">Langkah-langkah Pelayanan</h3>
    </div> --}}

    <div class="divide-y divide-gray-200">
        @forelse($getState() ?? [] as $index => $langkah)
            <div class="p-4 hover:bg-gray-50">
                <div class="flex gap-3 items-start">
                    <div class="mr-3">
                        <x-filament::badge
                            color="success"
                            class="h-8 w-8 rounded-full justify-center"
                        >
                            {{ $index + 1 }}
                        </x-filament::badge>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">
                            {{ $langkah['langkah'] }}
                        </div>

                        @if(!empty($langkah['keterangan']))
                            <div class="mt-1 text-sm text-gray-600">
                                {{ $langkah['keterangan'] }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="p-6 text-center">
                <div class="flex flex-col items-center justify-center space-y-1 text-gray-500">
                    <div class="flex items-center justify-center h-12 w-12 bg-gray-100 rounded-full mb-2">
                        <x-heroicon-o-question-mark-circle class="h-6 w-6" />
                    </div>
                    <span class="font-medium">Tidak ada prosedur</span>
                    <span class="text-xs">Belum ada langkah-langkah yang ditentukan untuk layanan ini</span>
                </div>
            </div>
        @endforelse
    </div>
</div>