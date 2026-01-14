@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center gap-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-2 rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed shadow-inner">
                ‹
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 rounded-xl bg-white text-purple-600 border border-purple-200 hover:border-purple-400 hover:text-purple-700 shadow-sm hover:shadow transition-all duration-200">
                ‹
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-3 py-2 text-gray-400">...</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-4 py-2 rounded-xl bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-semibold shadow-lg">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="px-4 py-2 rounded-xl bg-white text-gray-700 border border-gray-200 hover:border-purple-300 hover:text-purple-700 shadow-sm hover:shadow transition-all duration-200">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 rounded-xl bg-white text-purple-600 border border-purple-200 hover:border-purple-400 hover:text-purple-700 shadow-sm hover:shadow transition-all duration-200">
                ›
            </a>
        @else
            <span class="px-3 py-2 rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed shadow-inner">
                ›
            </span>
        @endif
    </nav>
@endif
