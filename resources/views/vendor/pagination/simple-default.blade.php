@if ($paginator->hasPages())
    <nav style="margin-top:1rem;">
        <ul style="display:flex;gap:0.5rem;list-style:none;padding:0;margin:0;">
            @if ($paginator->onFirstPage())
                <li><span style="padding:0.5rem;color:#9ca3af;">Previous</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" class="btn">Previous</a></li>
            @endif
            <li><span style="padding:0.5rem;">Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</span></li>
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" class="btn">Next</a></li>
            @else
                <li><span style="padding:0.5rem;color:#9ca3af;">Next</span></li>
            @endif
        </ul>
    </nav>
@endif
