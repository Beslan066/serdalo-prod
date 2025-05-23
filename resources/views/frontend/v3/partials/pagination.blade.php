@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="cm-pagination-container flex items-center justify-between">
        <div class="cm-pagination-m flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="cm-pagination-m-link">
                    {!! __('Начало') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="cm-pagination-m-link cm-pagination-m-link--effects">
                    {!! __('pagination.previous') !!}
                </a>
            @endif
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="cm-pagination-m-link cm-pagination-m-link--effects">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="cm-pagination-m-link">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>
        <div class="cm-pagination-desktop hidden sm:flex-1 sm:flex flex-col sm:items-center sm:justify-between">
            <div class="mb-2.5">
                <span class="cm-pagination-links relative z-0 inline-flex gap-2">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span class="cm-pagination-link-disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="cm-pagination-link" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="cm-pagination-link cm-pagination-link--effects" aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif
                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span class="cm-pagination-link-disabled" aria-disabled="true">
                                <span class="cm-pagination-link">{{ $element }}</span>
                            </span>
                        @endif
                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span class="cm-pagination-link-disabled" aria-current="page">
                                        <span class="cm-pagination-link">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="cm-pagination-link cm-pagination-link--effects" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="cm-pagination-link cm-pagination-link--effects" aria-label="{{ __('pagination.next') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span class="cm-pagination-link-disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="cm-pagination-link" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
            <div class="cm-pagination-text">
                <p class="text-sm text-gray-700 leading-5">
                    {!! __('Показано с') !!}
                    @if ($paginator->firstItem())
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        {!! __('по') !!}
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('из') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('результатов') !!}
                </p>
            </div>
        </div>
    </nav>
@endif
