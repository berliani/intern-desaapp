@if ($paginator->hasPages())
    <div class="pagination-container pb-8">
        <div class="flex flex-wrap items-center justify-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center justify-center w-10 h-10 px-4 py-2 text-sm opacity-50 bg-white border rounded-full text-gray-400 cursor-not-allowed select-none">
                    <i class="fas fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" 
                   class="relative inline-flex items-center justify-center w-10 h-10 px-4 py-2 text-sm text-gray-700 transition-colors bg-white border rounded-full shadow-sm hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span aria-disabled="true" class="relative inline-flex items-center justify-center w-10 h-10 px-4 py-2 text-sm text-gray-700 bg-white border rounded-full select-none">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="relative inline-flex items-center justify-center w-10 h-10 px-4 py-2 text-sm font-bold text-white bg-emerald-600 border border-emerald-600 rounded-full shadow-sm select-none">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" 
                               class="relative inline-flex items-center justify-center w-10 h-10 px-4 py-2 text-sm text-gray-700 transition-colors bg-white border rounded-full shadow-sm hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1" 
                               aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" 
                   class="relative inline-flex items-center justify-center w-10 h-10 px-4 py-2 text-sm text-gray-700 transition-colors bg-white border rounded-full shadow-sm hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="relative inline-flex items-center justify-center w-10 h-10 px-4 py-2 text-sm opacity-50 bg-white border rounded-full text-gray-400 cursor-not-allowed select-none">
                    <i class="fas fa-chevron-right"></i>
                </span>
            @endif
        </div>
        
        <div class="mt-3 text-center text-sm text-gray-500">
            <p>
                {!! __('Menampilkan') !!}
                <span class="font-medium">{{ $paginator->firstItem() }}</span>
                {!! __('sampai') !!}
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
                {!! __('dari') !!}
                <span class="font-medium">{{ $paginator->total() }}</span>
                {!! __('hasil') !!}
            </p>
        </div>
    </div>
@endif 