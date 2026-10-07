@if ($paginator->hasPages())
    <nav class="pag-custom">
        {{-- Mobile: prev/next only --}}
        <div class="pag-mobile">
            @if ($paginator->onFirstPage())
                <span class="pag-btn disabled">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pag-btn" rel="prev">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Sebelumnya
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pag-btn" rel="next">
                    Selanjutnya
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="pag-btn disabled">
                    Selanjutnya
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>

        {{-- Desktop: full pagination --}}
        <div class="pag-desktop">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="pag-arrow disabled">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pag-arrow" rel="prev" aria-label="Sebelumnya">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endif

            {{-- Page numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="pag-dots">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pag-num active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pag-num" aria-label="Halaman {{ $page }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pag-arrow" rel="next" aria-label="Selanjutnya">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="pag-arrow disabled">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>
    </nav>

    <style>
    .pag-custom {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 16px 0 4px;
    }

    /* Mobile */
    .pag-mobile {
        display: flex;
        gap: 8px;
        width: 100%;
    }
    .pag-mobile .pag-btn {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 600;
        border-radius: var(--radius-lg);
        background: var(--surface-container);
        color: var(--on-surface);
        text-decoration: none;
        transition: all .15s;
    }
    .pag-mobile .pag-btn:hover {
        background: var(--surface-container-high);
    }
    .pag-mobile .pag-btn.disabled {
        opacity: .4;
        pointer-events: none;
    }

    /* Desktop */
    .pag-desktop {
        display: none;
        align-items: center;
        gap: 4px;
    }
    .pag-arrow {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: var(--radius-lg);
        color: var(--on-surface-variant);
        text-decoration: none;
        transition: all .15s;
    }
    .pag-arrow:hover {
        background: var(--surface-container);
        color: var(--on-surface);
    }
    .pag-arrow.disabled {
        opacity: .3;
        pointer-events: none;
    }
    .pag-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 8px;
        font-size: 14px;
        font-weight: 600;
        border-radius: var(--radius-lg);
        color: var(--on-surface-variant);
        text-decoration: none;
        transition: all .15s;
    }
    .pag-num:hover {
        background: var(--surface-container);
        color: var(--on-surface);
    }
    .pag-num.active {
        background: var(--primary);
        color: #fff;
    }
    .pag-dots {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 24px;
        height: 36px;
        font-size: 14px;
        color: var(--on-surface-variant);
        letter-spacing: 2px;
    }

    @media (min-width: 640px) {
        .pag-mobile { display: none; }
        .pag-desktop { display: flex; }
    }
    </style>
@endif
