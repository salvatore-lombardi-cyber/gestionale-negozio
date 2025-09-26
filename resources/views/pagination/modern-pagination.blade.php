@if ($paginator->hasPages())
<style>
/* Modern Pagination Styles */
.modern-pagination-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    padding: 1rem 0;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

.modern-pagination-item {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 42px;
    height: 42px;
    padding: 10px 14px;
    margin: 0 3px;
    font-size: 0.95rem;
    font-weight: 600;
    color: #4a5568;
    background: rgba(255, 255, 255, 0.95);
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s ease;
    backdrop-filter: blur(15px);
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.modern-pagination-item:hover {
    color: #029D7E;
    background: rgba(2, 157, 126, 0.12);
    border-color: #029D7E;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(2, 157, 126, 0.2);
    text-decoration: none;
}

.modern-pagination-item.active {
    color: white;
    background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
    border-color: #029D7E;
    box-shadow: 0 8px 20px rgba(2, 157, 126, 0.35);
    font-weight: 700;
    transform: translateY(-1px);
}

.modern-pagination-item.disabled {
    color: #d1d5db;
    background: rgba(249, 250, 251, 0.5);
    border-color: #f3f4f6;
    cursor: not-allowed;
    opacity: 0.6;
}

.modern-pagination-item.disabled:hover {
    color: #d1d5db;
    background: rgba(249, 250, 251, 0.5);
    border-color: #f3f4f6;
    transform: none;
    box-shadow: none;
}

.modern-pagination-item i {
    font-size: 0.85rem;
}

.pagination-info {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
    color: #4a5568;
    margin-left: 1.5rem;
    padding: 10px 18px;
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(2, 157, 126, 0.1);
    border-radius: 12px;
    backdrop-filter: blur(15px);
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

/* Responsive */
@media (max-width: 768px) {
    .modern-pagination-wrapper {
        flex-wrap: wrap;
        gap: 4px;
    }
    
    .modern-pagination-item {
        min-width: 35px;
        height: 35px;
        font-size: 0.8rem;
        margin: 0 1px;
    }
    
    .pagination-info {
        margin-left: 0;
        margin-top: 0.5rem;
        font-size: 0.8rem;
    }
}

@media (max-width: 480px) {
    .modern-pagination-item {
        min-width: 32px;
        height: 32px;
        padding: 6px 8px;
    }
    
    .modern-pagination-item span {
        display: none;
    }
    
    .modern-pagination-item i {
        font-size: 0.8rem;
    }
}
</style>

<nav role="navigation" aria-label="Pagination Navigation">
    <div class="modern-pagination-wrapper">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="modern-pagination-item disabled">
                <i class="bi bi-chevron-left"></i>
                <span class="ms-1 d-none d-sm-inline">Precedente</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="modern-pagination-item" rel="prev">
                <i class="bi bi-chevron-left"></i>
                <span class="ms-1 d-none d-sm-inline">Precedente</span>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="modern-pagination-item disabled">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="modern-pagination-item active" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="modern-pagination-item">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="modern-pagination-item" rel="next">
                <span class="me-1 d-none d-sm-inline">Successiva</span>
                <i class="bi bi-chevron-right"></i>
            </a>
        @else
            <span class="modern-pagination-item disabled">
                <span class="me-1 d-none d-sm-inline">Successiva</span>
                <i class="bi bi-chevron-right"></i>
            </span>
        @endif

        {{-- Pagination Info --}}
        <div class="pagination-info">
            Pagina {{ $paginator->currentPage() }} di {{ $paginator->lastPage() }}
            <span class="d-none d-md-inline">
                ({{ $paginator->firstItem() }}-{{ $paginator->lastItem() }} di {{ $paginator->total() }} elementi)
            </span>
        </div>
    </div>
</nav>
@endif