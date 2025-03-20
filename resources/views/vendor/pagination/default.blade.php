@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center space-x-2 mt-4">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1 text-gray-400 border rounded-md cursor-not-allowed">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 text-black border rounded-md hover:bg-gray-200">Previous</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 py-1 text-gray-500">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-1 text-white bg-blue-500 border rounded-md">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1 text-black border rounded-md hover:bg-gray-200">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 text-black border rounded-md hover:bg-gray-200">Next</a>
        @else
            <span class="px-3 py-1 text-gray-400 border rounded-md cursor-not-allowed">Next</span>
        @endif
    </nav>
@endif
