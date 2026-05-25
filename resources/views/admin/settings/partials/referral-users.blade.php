<div class="card-body">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h5 style="font-weight: 600; color: #1e293b; margin: 0;">
            <i class="mdi mdi-account-group"></i> Liste des Utilisateurs & Codes Parrain
        </h5>
        <div>
            <input
                type="text"
                id="searchUsers"
                class="form-control"
                placeholder="Rechercher un utilisateur..."
                style="border-radius: 6px; padding: 0.5rem 1rem; width: 300px;"
            >
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover" style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
            <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <tr>
                    <th style="padding: 1rem; border: none;">ID</th>
                    <th style="padding: 1rem; border: none;">Utilisateur</th>
                    <th style="padding: 1rem; border: none;">Email / Téléphone</th>
                    <th style="padding: 1rem; border: none;">Code Promo</th>
                    <th style="padding: 1rem; border: none;">Parrainé par</th>
                    <th style="padding: 1rem; border: none;">Nb Filleuls</th>
                    <th style="padding: 1rem; border: none;">Commissions gagnées</th>
                </tr>
            </thead>
            <tbody id="usersTableBody">
                @forelse($users as $user)
                    <tr class="user-row" style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 1rem;">{{ $user->id }}</td>
                        <td style="padding: 1rem;">
                            <div style="display: flex; align-items: center;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; margin-right: 0.75rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: #1e293b;">{{ $user->name }}</div>
                                    <div style="font-size: 0.75rem; color: #64748b;">
                                        <span class="badge" style="background: {{ $user->role === 'admin' ? '#ef4444' : ($user->role === 'recruiter' ? '#3b82f6' : '#10b981') }}; color: white; font-size: 0.7rem;">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 1rem;">
                            <div>
                                @if($user->email)
                                    <div style="font-size: 0.875rem; color: #334155;">
                                        <i class="mdi mdi-email"></i> {{ $user->email }}
                                    </div>
                                @endif
                                @if($user->phone)
                                    <div style="font-size: 0.875rem; color: #64748b;">
                                        <i class="mdi mdi-phone"></i> {{ $user->phone }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <code style="background: #f1f5f9; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; color: #667eea; font-size: 1rem;">
                                    {{ $user->referral_code ?? 'N/A' }}
                                </code>
                                @if($user->referral_code)
                                    <button
                                        onclick="copyToClipboard('{{ $user->referral_code }}')"
                                        class="btn btn-sm btn-outline-primary"
                                        style="border-radius: 6px; padding: 0.25rem 0.5rem;"
                                        title="Copier le code"
                                    >
                                        <i class="mdi mdi-content-copy"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                        <td style="padding: 1rem;">
                            @if($user->referrer)
                                <div style="font-size: 0.875rem; color: #334155;">
                                    <i class="mdi mdi-account-arrow-left"></i> {{ $user->referrer->name }}
                                    <br>
                                    <small style="color: #64748b;">Code: <code>{{ $user->referrer->referral_code }}</code></small>
                                </div>
                            @else
                                <span style="color: #94a3b8; font-style: italic;">Aucun parrain</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <span class="badge" style="background: {{ $user->referrals_count > 0 ? '#10b981' : '#94a3b8' }}; color: white; font-size: 0.875rem; padding: 0.5rem 1rem; border-radius: 6px;">
                                {{ $user->referrals_count }}
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600; color: #10b981;">
                                {{ number_format($user->getTotalEarnedCommissions(), 0, ',', ' ') }} FCFA
                            </div>
                            <small style="color: #64748b;">
                                {{ $user->earnedCommissions()->count() }} transactions
                            </small>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 3rem;">
                            <i class="mdi mdi-account-off" style="font-size: 3rem; color: #cbd5e1;"></i>
                            <p style="color: #64748b; margin-top: 1rem;">Aucun utilisateur trouvé</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div style="color: #64748b; font-size: 0.875rem;">
            Affichage de {{ $users->firstItem() ?? 0 }} à {{ $users->lastItem() ?? 0 }} sur {{ $users->total() }} utilisateurs
        </div>
        <div>
            {{ $users->links() }}
        </div>
    </div>
</div>

<script>
    // Fonction de recherche
    document.getElementById('searchUsers')?.addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.user-row');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Fonction pour copier le code
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            // Afficher un toast ou message de succès
            alert('Code copié : ' + text);
        }).catch(err => {
            console.error('Erreur lors de la copie:', err);
        });
    }
</script>
