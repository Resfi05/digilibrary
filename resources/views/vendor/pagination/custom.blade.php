@if ($paginator->hasPages())
    <nav style="display:flex;align-items:center;gap:6px;justify-content:center;flex-wrap:wrap;">

        {{-- Tombol Previous --}}
        @if ($paginator->onFirstPage())
            <span style="padding:6px 12px;border-radius:8px;font-size:13px;color:#d1d5db;background:#f9fafb;border:1px solid #e5e7eb;cursor:not-allowed;">← Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" style="padding:6px 12px;border-radius:8px;font-size:13px;color:#6366f1;background:#fff;border:1px solid #e5e7eb;text-decoration:none;transition:all 0.15s;" onmouseover="this.style.background='#eff6ff'" onmouseout="this.style.background='#fff'">← Prev</a>
        @endif

        {{-- Nomor Halaman --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span style="padding:6px 10px;font-size:13px;color:#9ca3af;">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="padding:6px 12px;border-radius:8px;font-size:13px;font-weight:700;color:#fff;background:#6366f1;border:1px solid #6366f1;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="padding:6px 12px;border-radius:8px;font-size:13px;color:#374151;background:#fff;border:1px solid #e5e7eb;text-decoration:none;transition:all 0.15s;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='#fff'">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Tombol Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" style="padding:6px 12px;border-radius:8px;font-size:13px;color:#6366f1;background:#fff;border:1px solid #e5e7eb;text-decoration:none;transition:all 0.15s;" onmouseover="this.style.background='#eff6ff'" onmouseout="this.style.background='#fff'">Next →</a>
        @else
            <span style="padding:6px 12px;border-radius:8px;font-size:13px;color:#d1d5db;background:#f9fafb;border:1px solid #e5e7eb;cursor:not-allowed;">Next →</span>
        @endif

    </nav>
@endif