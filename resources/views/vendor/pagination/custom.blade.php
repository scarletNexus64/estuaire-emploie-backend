@if ($paginator->hasPages())
    <nav class="pagination-wrapper" role="navigation" aria-label="Pagination">
        <div class="pagination-info">
            <span>Affichage de <strong>{{ $paginator->firstItem() }}</strong> à <strong>{{ $paginator->lastItem() }}</strong> sur <strong>{{ $paginator->total() }}</strong> résultats</span>
        </div>

        <ul class="pagination">
            {{-- Bouton Précédent --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">&laquo; Précédent</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; Précédent</a>
                </li>
            @endif

            {{-- Numéros de page --}}
            @foreach ($elements as $element)
                {{-- Séparateur "..." --}}
                @if (is_string($element))
                    <li class="page-item disabled">
                        <span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                {{-- Liens de page --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Bouton Suivant --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Suivant &raquo;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">Suivant &raquo;</span>
                </li>
            @endif
        </ul>
    </nav>

    <style>
        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .pagination-info {
            font-size: 0.875rem;
            color: var(--secondary);
        }

        .pagination-info strong {
            color: var(--dark);
            font-weight: 600;
        }

        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 0.25rem;
        }

        .page-item .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0.875rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--dark);
            background: white;
            border: 2px solid var(--border);
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            min-width: 40px;
        }

        .page-item .page-link:hover {
            background: var(--light);
            border-color: var(--primary);
            color: var(--primary);
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .page-item.disabled .page-link {
            background: var(--light);
            color: var(--secondary);
            cursor: not-allowed;
            opacity: 0.6;
        }

        .page-item.disabled .page-link:hover {
            border-color: var(--border);
            color: var(--secondary);
        }

        @media (max-width: 768px) {
            .pagination-wrapper {
                flex-direction: column;
                align-items: stretch;
            }

            .pagination-info {
                text-align: center;
            }

            .pagination {
                justify-content: center;
                flex-wrap: wrap;
            }

            .page-item .page-link {
                padding: 0.375rem 0.625rem;
                font-size: 0.8125rem;
            }
        }
    </style>
@endif
