<div class="card-body">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h5 style="font-weight: 600; color: #1e293b; margin: 0;">
            <i class="mdi mdi-cash-multiple"></i> Historique des Commissions
        </h5>
        <div style="display: flex; gap: 1rem;">
            <select id="filterTransactionType" class="form-select" style="width: 200px; border-radius: 6px;">
                <option value="">Tous les types</option>
                <option value="paypal">PayPal</option>
                <option value="freemopay">FreeMoPay</option>
            </select>
            <input
                type="text"
                id="searchCommissions"
                class="form-control"
                placeholder="Rechercher..."
                style="border-radius: 6px; padding: 0.5rem 1rem; width: 300px;"
            >
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover" style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
            <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <tr>
                    <th style="padding: 1rem; border: none;">ID</th>
                    <th style="padding: 1rem; border: none;">Date</th>
                    <th style="padding: 1rem; border: none;">Parrain</th>
                    <th style="padding: 1rem; border: none;">Filleul</th>
                    <th style="padding: 1rem; border: none;">Type Transaction</th>
                    <th style="padding: 1rem; border: none;">Montant Recharge</th>
                    <th style="padding: 1rem; border: none;">Commission (%)</th>
                    <th style="padding: 1rem; border: none;">Commission (FCFA)</th>
                </tr>
            </thead>
            <tbody id="commissionsTableBody">
                @forelse($commissions as $commission)
                    <tr class="commission-row" data-type="{{ $commission->transaction_type }}" style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 1rem;">
                            <code style="background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 4px; color: #64748b;">
                                #{{ $commission->id }}
                            </code>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="font-size: 0.875rem; color: #334155;">
                                {{ $commission->created_at->format('d/m/Y') }}
                            </div>
                            <div style="font-size: 0.75rem; color: #64748b;">
                                {{ $commission->created_at->format('H:i') }}
                            </div>
                        </td>
                        <td style="padding: 1rem;">
                            @if($commission->referrer)
                                <div style="display: flex; align-items: center;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; margin-right: 0.5rem; font-size: 0.875rem;">
                                        {{ strtoupper(substr($commission->referrer->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #1e293b; font-size: 0.875rem;">
                                            {{ $commission->referrer->name }}
                                        </div>
                                        <div style="font-size: 0.75rem; color: #64748b;">
                                            {{ $commission->referrer->email ?? $commission->referrer->phone }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span style="color: #94a3b8; font-style: italic;">N/A</span>
                            @endif
                        </td>
                        <td style="padding: 1rem;">
                            @if($commission->referred)
                                <div style="display: flex; align-items: center;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; margin-right: 0.5rem; font-size: 0.875rem;">
                                        {{ strtoupper(substr($commission->referred->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #1e293b; font-size: 0.875rem;">
                                            {{ $commission->referred->name }}
                                        </div>
                                        <div style="font-size: 0.75rem; color: #64748b;">
                                            {{ $commission->referred->email ?? $commission->referred->phone }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span style="color: #94a3b8; font-style: italic;">N/A</span>
                            @endif
                        </td>
                        <td style="padding: 1rem;">
                            <span class="badge" style="background: {{ $commission->transaction_type === 'paypal' ? '#0070ba' : '#10b981' }}; color: white; font-size: 0.8rem; padding: 0.5rem 0.75rem; border-radius: 6px;">
                                <i class="mdi {{ $commission->transaction_type === 'paypal' ? 'mdi-paypal' : 'mdi-cash' }}"></i>
                                {{ strtoupper($commission->transaction_type) }}
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600; color: #334155;">
                                {{ number_format($commission->transaction_amount, 0, ',', ' ') }} FCFA
                            </div>
                            @if($commission->transaction_reference)
                                <small style="color: #64748b;">Ref: {{ Str::limit($commission->transaction_reference, 15) }}</small>
                            @endif
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <span class="badge" style="background: #f59e0b; color: white; font-size: 0.875rem; padding: 0.5rem 0.75rem; border-radius: 6px;">
                                {{ number_format($commission->commission_percentage, 2) }}%
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 700; color: #10b981; font-size: 1rem;">
                                +{{ number_format($commission->commission_amount, 0, ',', ' ') }} FCFA
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center" style="padding: 3rem;">
                            <i class="mdi mdi-cash-remove" style="font-size: 3rem; color: #cbd5e1;"></i>
                            <p style="color: #64748b; margin-top: 1rem;">Aucune commission enregistrée</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div style="color: #64748b; font-size: 0.875rem;">
            Affichage de {{ $commissions->firstItem() ?? 0 }} à {{ $commissions->lastItem() ?? 0 }} sur {{ $commissions->total() }} commissions
        </div>
        <div>
            {{ $commissions->links() }}
        </div>
    </div>
</div>

<script>
    // Fonction de recherche
    document.getElementById('searchCommissions')?.addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        filterCommissions();
    });

    // Fonction de filtrage par type
    document.getElementById('filterTransactionType')?.addEventListener('change', function(e) {
        filterCommissions();
    });

    function filterCommissions() {
        const searchTerm = document.getElementById('searchCommissions')?.value.toLowerCase() || '';
        const filterType = document.getElementById('filterTransactionType')?.value || '';
        const rows = document.querySelectorAll('.commission-row');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const type = row.getAttribute('data-type');

            const matchesSearch = text.includes(searchTerm);
            const matchesType = !filterType || type === filterType;

            if (matchesSearch && matchesType) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
