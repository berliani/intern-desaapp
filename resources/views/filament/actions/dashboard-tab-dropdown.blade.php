<div x-data="{ open: false }" @keydown.escape.stop="open = false" @click.away="open = false" class="relative">
    <button
        @click="open = !open"
        type="button"
        class="flex items-center justify-center gap-1 rounded-lg bg-primary-600 px-3 py-2 text-sm font-medium text-white outline-none hover:bg-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-primary-500 dark:hover:bg-primary-400 dark:focus:ring-primary-400 dark:focus:ring-offset-dark-950"
    >
        <div wire:loading.delay wire:target="switchTab" class="mr-1">
            <x-filament::loading-indicator class="h-4 w-4" />
        </div>

        <x-filament::icon
            icon="{{ match($this->activeTab) {
                'keuangan' => 'heroicon-m-banknotes',
                'penduduk' => 'heroicon-m-users',
                'inventaris' => 'heroicon-m-cube',
                'bansos' => 'heroicon-m-gift',
                'penerima_bansos' => 'heroicon-m-user-group',
                'pengaduan' => 'heroicon-m-megaphone',
                'layanan' => 'heroicon-m-clipboard-document-list',
                default => 'heroicon-m-squares-2x2'
            } }}"
            class="h-5 w-5"
            wire:loading.class="animate-pulse"
            wire:target="switchTab"
        />
        <span>
            {{ match($this->activeTab) {
                'keuangan' => 'Keuangan Desa',
                'penduduk' => 'Data Penduduk',
                'inventaris' => 'Inventaris Desa',
                'bansos' => 'Bantuan Sosial',
                'penerima_bansos' => 'Penerima Bantuan',
                'pengaduan' => 'Pengaduan Warga',
                'layanan' => 'Layanan Desa',
                default => 'Dashboard'
            } }}
        </span>
        <x-filament::icon
            icon="heroicon-m-chevron-down"
            class="h-4 w-4 ml-1"
        />
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute left-0 z-50 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 focus:outline-none"
        style="display: none;"
        @click="open = false"
    >
        <div class="py-1">
            <button
                wire:click="switchTab('keuangan')"
                wire:loading.attr="disabled"
                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-left {{ $this->activeTab === 'keuangan' ? 'text-primary-600 bg-primary-50 dark:bg-primary-950 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700' }}"
            >
                <div wire:loading.delay wire:target="switchTab('keuangan')" class="mr-1">
                    <x-filament::loading-indicator class="h-4 w-4" />
                </div>
                <x-filament::icon icon="heroicon-m-banknotes" class="h-5 w-5" />
                Keuangan Desa
            </button>

            <button
                wire:click="switchTab('penduduk')"
                wire:loading.attr="disabled"
                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-left {{ $this->activeTab === 'penduduk' ? 'text-primary-600 bg-primary-50 dark:bg-primary-950 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700' }}"
            >
                <div wire:loading.delay wire:target="switchTab('penduduk')" class="mr-1">
                    <x-filament::loading-indicator class="h-4 w-4" />
                </div>
                <x-filament::icon icon="heroicon-m-users" class="h-5 w-5" />
                Data Penduduk
            </button>

            <button
                wire:click="switchTab('inventaris')"
                wire:loading.attr="disabled"
                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-left {{ $this->activeTab === 'inventaris' ? 'text-primary-600 bg-primary-50 dark:bg-primary-950 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700' }}"
            >
                <div wire:loading.delay wire:target="switchTab('inventaris')" class="mr-1">
                    <x-filament::loading-indicator class="h-4 w-4" />
                </div>
                <x-filament::icon icon="heroicon-m-cube" class="h-5 w-5" />
                Inventaris Desa
            </button>

            <button
                wire:click="switchTab('bansos')"
                wire:loading.attr="disabled"
                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-left {{ $this->activeTab === 'bansos' ? 'text-primary-600 bg-primary-50 dark:bg-primary-950 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700' }}"
            >
                <div wire:loading.delay wire:target="switchTab('bansos')" class="mr-1">
                    <x-filament::loading-indicator class="h-4 w-4" />
                </div>
                <x-filament::icon icon="heroicon-m-gift" class="h-5 w-5" />
                Bantuan Sosial
            </button>

            <button
                wire:click="switchTab('penerima_bansos')"
                wire:loading.attr="disabled"
                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-left {{ $this->activeTab === 'penerima_bansos' ? 'text-primary-600 bg-primary-50 dark:bg-primary-950 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700' }}"
            >
                <div wire:loading.delay wire:target="switchTab('penerima_bansos')" class="mr-1">
                    <x-filament::loading-indicator class="h-4 w-4" />
                </div>
                <x-filament::icon icon="heroicon-m-user-group" class="h-5 w-5" />
                Penerima Bantuan
            </button>

            <button
                wire:click="switchTab('pengaduan')"
                wire:loading.attr="disabled"
                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-left {{ $this->activeTab === 'pengaduan' ? 'text-primary-600 bg-primary-50 dark:bg-primary-950 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700' }}"
            >
                <div wire:loading.delay wire:target="switchTab('pengaduan')" class="mr-1">
                    <x-filament::loading-indicator class="h-4 w-4" />
                </div>
                <x-filament::icon icon="heroicon-m-megaphone" class="h-5 w-5" />
                Pengaduan Warga
            </button>

            <button
                wire:click="switchTab('layanan')"
                wire:loading.attr="disabled"
                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-left {{ $this->activeTab === 'layanan' ? 'text-primary-600 bg-primary-50 dark:bg-primary-950 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700' }}"
            >
                <div wire:loading.delay wire:target="switchTab('layanan')" class="mr-1">
                    <x-filament::loading-indicator class="h-4 w-4" />
                </div>
                <x-filament::icon icon="heroicon-m-clipboard-document-list" class="h-5 w-5" />
                Layanan Desa
            </button>
        </div>
    </div>
</div>