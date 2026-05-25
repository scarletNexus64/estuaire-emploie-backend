@extends('admin.layouts.app')

@section('title', 'Compte FREEMOPAY')
@section('page-title', 'Compte FREEMOPAY - Revenus & Retraits')

@section('content')
    <!-- Avertissement Recharges Wallet -->
    <div style="margin-bottom: 1rem; padding: 1rem 1.5rem; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 8px;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <i class="fas fa-info-circle" style="color: #856404; font-size: 1.5rem;"></i>
            <div>
                <strong style="color: #856404;">Important :</strong>
                <span style="color: #856404;">Les recharges de wallet (wallet_recharge) appartiennent aux utilisateurs et ne sont PAS comptabilisées dans les revenus plateforme. Seuls les achats de services (abonnements, packs, etc.) sont des revenus pour la plateforme.</span>
            </div>
        </div>
    </div>

    <!-- Soldes par Provider -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
        <!-- FreeMoPay Balance Card -->
        <div class="card">
            <div style="padding: 2rem; text-align: center; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 16px;">
                <h3 style="color: white; font-size: 1rem; margin-bottom: 0.5rem; opacity: 0.9;">
                    <i class="fas fa-mobile-alt"></i> Solde FreeMoPay Disponible
                </h3>
                <h1 style="color: white; font-size: 2.5rem; font-weight: 800; margin: 0;">
                    {{ number_format($freemopayAvailableBalance, 0, ',', ' ') }}
                    <span style="font-size: 1.25rem; opacity: 0.8;">XAF</span>
                </h1>
                <div style="display: flex; justify-content: center; gap: 2rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.2);">
                    <div>
                        <p style="color: rgba(255,255,255,0.8); font-size: 0.8rem; margin: 0;">Revenus</p>
                        <p style="color: white; font-size: 1rem; font-weight: 700; margin: 0.25rem 0 0 0;">
                            {{ number_format($freemopayRevenue, 0, ',', ' ') }}
                        </p>
                    </div>
                    <div>
                        <p style="color: rgba(255,255,255,0.8); font-size: 0.8rem; margin: 0;">Retiré</p>
                        <p style="color: white; font-size: 1rem; font-weight: 700; margin: 0.25rem 0 0 0;">
                            {{ number_format($freemopayWithdrawn, 0, ',', ' ') }}
                        </p>
                    </div>
                </div>
                <button onclick="openWithdrawalModal('freemopay')" style="margin-top: 1.5rem; background: white; color: #f59e0b; font-weight: 600; padding: 0.75rem 2rem; border-radius: 8px; border: none; cursor: pointer; width: 100%; font-size: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: all 0.3s;">
                    <i class="fas fa-arrow-down"></i> Retirer via FreeMoPay
                </button>
            </div>
        </div>

        <!-- PayPal Balance Card -->
        <div class="card">
            <div style="padding: 2rem; text-align: center; background: linear-gradient(135deg, #0070ba 0%, #003087 100%); border-radius: 16px;">
                <h3 style="color: white; font-size: 1rem; margin-bottom: 0.5rem; opacity: 0.9;">
                    <i class="fab fa-paypal"></i> Solde PayPal Disponible
                </h3>
                <h1 style="color: white; font-size: 2.5rem; font-weight: 800; margin: 0;">
                    {{ number_format($paypalAvailableBalance, 2, '.', ',') }}
                    <span style="font-size: 1.25rem; opacity: 0.8;">USD</span>
                </h1>
                <div style="display: flex; justify-content: center; gap: 2rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.2);">
                    <div>
                        <p style="color: rgba(255,255,255,0.8); font-size: 0.8rem; margin: 0;">Revenus</p>
                        <p style="color: white; font-size: 1rem; font-weight: 700; margin: 0.25rem 0 0 0;">
                            {{ number_format($paypalRevenue, 2, '.', ',') }}
                        </p>
                    </div>
                    <div>
                        <p style="color: rgba(255,255,255,0.8); font-size: 0.8rem; margin: 0;">Retiré</p>
                        <p style="color: white; font-size: 1rem; font-weight: 700; margin: 0.25rem 0 0 0;">
                            {{ number_format($paypalWithdrawn, 2, '.', ',') }}
                        </p>
                    </div>
                </div>
                <button onclick="openWithdrawalModal('paypal')" style="margin-top: 1.5rem; background: white; color: #0070ba; font-weight: 600; padding: 0.75rem 2rem; border-radius: 8px; border: none; cursor: pointer; width: 100%; font-size: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: all 0.3s;">
                    <i class="fab fa-paypal"></i> Retirer via PayPal
                </button>
            </div>
        </div>
    </div>

    <!-- Tab Navigation & Content -->
    <div class="card">
        <!-- Tab Headers -->
        <div style="display: flex; border-bottom: 2px solid #e2e8f0; background: #f8fafc;">
            <button
                class="tab-button"
                data-tab="revenus"
                onclick="switchTab('revenus')"
                style="flex: 1; padding: 1.25rem 2rem; border: none; background: transparent; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s; border-bottom: 3px solid transparent; color: #64748b;"
            >
                <i class="fas fa-coins"></i>
                Revenus Plateforme
                <span class="badge badge-success" style="margin-left: 0.5rem;">{{ $revenues->total() }}</span>
            </button>
            <button
                class="tab-button"
                data-tab="retraits"
                onclick="switchTab('retraits')"
                style="flex: 1; padding: 1.25rem 2rem; border: none; background: transparent; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s; border-bottom: 3px solid transparent; color: #64748b;"
            >
                <i class="fas fa-arrow-down"></i>
                Historique Retraits
                <span class="badge badge-danger" style="margin-left: 0.5rem;">{{ $withdrawals->total() }}</span>
            </button>
        </div>

        <!-- Tab Content Container -->
        <div style="position: relative; overflow: hidden;">
            <!-- Revenus Tab -->
            <div id="tab-revenus" class="tab-content" style="display: block;">
                <div class="card-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-coins" style="font-size: 2rem;"></i>
                            <div>
                                <h3 style="margin: 0; color: white;">📊 Revenus Plateforme (Achats de Services)</h3>
                                <p style="margin: 0; opacity: 0.9; font-size: 0.875rem;">{{ $revenues->total() }} revenu(s) enregistré(s)</p>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <p style="margin: 0; opacity: 0.8; font-size: 0.875rem;">Total Revenus</p>
                            <h3 style="margin: 0; color: white; font-weight: 700;">{{ number_format($totalRevenue, 0, ',', ' ') }} XAF</h3>
                        </div>
                    </div>
                </div>

                @if($revenues->count() > 0)
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Montant</th>
                                <th>Type Service</th>
                                <th>Provider</th>
                                <th>Méthode</th>
                                <th>Référence</th>
                                <th>Client</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($revenues as $revenue)
                            <tr>
                                <td><strong>#{{ $revenue['id'] }}</strong></td>
                                <td>
                                    <span class="badge badge-success" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                                        {{ number_format($revenue['amount'], 0, ',', ' ') }} XAF
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $revenue['payment_type'] ?? 'N/A')) }}</span>
                                </td>
                                <td>
                                    @if($revenue['provider'] === 'paypal')
                                        <span style="color: #0070ba; font-weight: 600;">
                                            <i class="fab fa-paypal"></i> PayPal
                                        </span>
                                    @elseif($revenue['provider'] === 'freemopay')
                                        <span style="color: #f59e0b; font-weight: 600;">
                                            <i class="fas fa-mobile-alt"></i> FreeMoPay
                                        </span>
                                    @else
                                        <span style="color: #6c757d;">{{ $revenue['provider'] }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($revenue['payment_method'] === 'mtn_money')
                                        <span style="color: #FFB300;"><i class="fas fa-mobile-alt"></i> MTN</span>
                                    @elseif($revenue['payment_method'] === 'orange_money')
                                        <span style="color: #FF6600;"><i class="fas fa-mobile-alt"></i> Orange</span>
                                    @elseif($revenue['payment_method'] === 'paypal')
                                        <span style="color: #0070ba;"><i class="fab fa-paypal"></i> PayPal</span>
                                    @else
                                        <span style="color: #6c757d;">{{ ucfirst($revenue['payment_method'] ?? 'N/A') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($revenue['transaction_reference'])
                                        <code style="font-size: 0.75rem; color: #6c757d;">{{ Str::limit($revenue['transaction_reference'], 20) }}</code>
                                    @else
                                        <span style="color: #6c757d;">-</span>
                                    @endif
                                </td>
                                <td>
                                    <i class="fas fa-user" style="color: #6c757d; margin-right: 0.5rem;"></i>
                                    {{ $revenue['user_name'] }}
                                </td>
                                <td>
                                    <i class="fas fa-calendar-alt" style="color: #6c757d; margin-right: 0.5rem;"></i>
                                    {{ $revenue['created_at'] }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div style="padding: 1.5rem; border-top: 1px solid #e2e8f0;">
                    {{ $revenues->links() }}
                </div>
                @else
                <div style="text-align: center; padding: 4rem 2rem;">
                    <i class="fas fa-coins" style="font-size: 4rem; color: #6c757d; opacity: 0.3; margin-bottom: 1rem;"></i>
                    <p style="color: #6c757d; font-size: 1.125rem;">Aucun revenu pour le moment</p>
                </div>
                @endif
            </div>

            <!-- Retraits Tab -->
            <div id="tab-retraits" class="tab-content" style="display: none;">
                <div class="card-header" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-arrow-down" style="font-size: 2rem;"></i>
                            <div>
                                <h3 style="margin: 0; color: white;">💸 Historique des Retraits</h3>
                                <p style="margin: 0; opacity: 0.9; font-size: 0.875rem;">{{ $withdrawals->total() }} retrait(s) enregistré(s)</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1.5rem;">
                            <div style="text-align: right;">
                                <p style="margin: 0; opacity: 0.8; font-size: 0.875rem;">Total Retiré</p>
                                <h3 style="margin: 0; color: white; font-weight: 700;">{{ number_format($totalWithdrawn, 0, ',', ' ') }} XAF</h3>
                            </div>
                            <button onclick="openWithdrawalModal()" class="btn" style="background: white; color: #dc3545; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: all 0.3s;">
                                <i class="fas fa-arrow-down"></i>
                                Nouveau Retrait
                            </button>
                        </div>
                    </div>
                </div>

                @if($withdrawals->count() > 0)
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Méthode</th>
                                <th>Téléphone</th>
                                <th>Admin</th>
                                <th>Référence</th>
                                <th>Notes</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdrawals as $withdrawal)
                            <tr>
                                <td><strong>#{{ $withdrawal['id'] }}</strong></td>
                                <td>
                                    <span class="badge badge-danger" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                                        - {{ number_format($withdrawal['amount'], 0, ',', ' ') }} XAF
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $withdrawal['status_badge']['class'] }}">
                                        {{ $withdrawal['status_badge']['label'] }}
                                    </span>
                                </td>
                                <td>
                                    @if($withdrawal['payment_method'] === 'momo')
                                        <span style="color: #FFB300;">
                                            <i class="fas fa-mobile-alt"></i> MTN MoMo
                                        </span>
                                    @else
                                        <span style="color: #FF6600;">
                                            <i class="fas fa-mobile-alt"></i> Orange Money
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <code style="font-size: 0.875rem;">{{ $withdrawal['payment_account'] }}</code>
                                </td>
                                <td>
                                    <i class="fas fa-user-shield" style="color: #dc3545; margin-right: 0.5rem;"></i>
                                    {{ $withdrawal['admin_name'] }}
                                </td>
                                <td>
                                    @if($withdrawal['freemopay_reference'])
                                        <code style="color: #dc3545; font-size: 0.75rem;">{{ Str::limit($withdrawal['freemopay_reference'], 20) }}</code>
                                    @else
                                        <span style="color: #6c757d;">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($withdrawal['admin_notes'])
                                        <span style="font-size: 0.875rem;" title="{{ $withdrawal['admin_notes'] }}">
                                            {{ Str::limit($withdrawal['admin_notes'], 30) }}
                                        </span>
                                    @else
                                        <span style="color: #6c757d;">-</span>
                                    @endif
                                </td>
                                <td>
                                    <i class="fas fa-calendar-alt" style="color: #6c757d; margin-right: 0.5rem;"></i>
                                    {{ $withdrawal['created_at'] }}
                                    @if($withdrawal['completed_at'])
                                        <br><small style="color: #28a745;">✓ {{ $withdrawal['completed_at'] }}</small>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div style="padding: 1.5rem; border-top: 1px solid #e2e8f0;">
                    {{ $withdrawals->links() }}
                </div>
                @else
                <div style="text-align: center; padding: 4rem 2rem;">
                    <i class="fas fa-arrow-down" style="font-size: 4rem; color: #6c757d; opacity: 0.3; margin-bottom: 1rem;"></i>
                    <p style="color: #6c757d; font-size: 1.125rem;">Aucun retrait pour le moment</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de vérification PIN -->
    <div id="pinModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 16px; padding: 2rem; max-width: 400px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <h3 style="margin: 0 0 1rem 0; color: #667eea; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-lock"></i>
                Vérification de sécurité
            </h3>
            <p style="color: #6c757d; margin-bottom: 1.5rem;">Entrez votre code PIN pour continuer</p>

            <div style="margin-bottom: 1.5rem;">
                <input type="password" id="pinInput" placeholder="Code PIN" style="width: 100%; padding: 0.75rem; border: 2px solid #dee2e6; border-radius: 8px; font-size: 1rem;" maxlength="4">
                <p id="pinError" style="color: #dc3545; font-size: 0.875rem; margin: 0.5rem 0 0 0; display: none;"></p>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button onclick="closePinModal()" style="flex: 1; padding: 0.75rem; border: 2px solid #dee2e6; background: white; color: #333; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                    Annuler
                </button>
                <button onclick="verifyPin()" style="flex: 1; padding: 0.75rem; border: none; background: #667eea; color: white; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                    Vérifier
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de retrait FreeMoPay -->
    <div id="withdrawalModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 16px; padding: 2rem; max-width: 500px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); max-height: 90vh; overflow-y: auto;">
            <h3 style="margin: 0 0 1rem 0; color: #f59e0b; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-mobile-alt"></i>
                Effectuer un retrait FreeMoPay
            </h3>
            <p style="color: #6c757d; margin-bottom: 1.5rem;">Solde disponible: <strong id="availableBalance">Chargement...</strong></p>

            <form id="withdrawalForm">
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 600;">Montant (XAF)</label>
                    <input type="number" id="amount" name="amount" placeholder="Minimum 50 XAF" min="50" step="1" required style="width: 100%; padding: 0.75rem; border: 2px solid #dee2e6; border-radius: 8px; font-size: 1rem;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 600;">Méthode de paiement</label>
                    <select id="paymentMethod" name="payment_method" required style="width: 100%; padding: 0.75rem; border: 2px solid #dee2e6; border-radius: 8px; font-size: 1rem;">
                        <option value="">Choisir...</option>
                        <option value="momo">MTN Mobile Money</option>
                        <option value="om">Orange Money</option>
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 600;">Numéro de téléphone</label>
                    <input type="tel" id="phone" name="phone" placeholder="237XXXXXXXXX" required style="width: 100%; padding: 0.75rem; border: 2px solid #dee2e6; border-radius: 8px; font-size: 1rem;">
                    <small style="color: #6c757d; font-size: 0.875rem;">Format: 237XXXXXXXXX (Cameroun) ou 243XXXXXXXXX (RDC)</small>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 600;">Notes (optionnel)</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Notes administratives..." style="width: 100%; padding: 0.75rem; border: 2px solid #dee2e6; border-radius: 8px; font-size: 1rem; resize: vertical;"></textarea>
                </div>

                <div id="withdrawalError" style="display: none; padding: 1rem; background: #fee; border-left: 4px solid #dc3545; border-radius: 8px; margin-bottom: 1rem;">
                    <p style="margin: 0; color: #dc3545;"></p>
                </div>

                <div id="withdrawalSuccess" style="display: none; padding: 1rem; background: #efe; border-left: 4px solid #28a745; border-radius: 8px; margin-bottom: 1rem;">
                    <p style="margin: 0; color: #28a745;"></p>
                </div>

                <div id="withdrawalProcessing" style="display: none; padding: 1rem; background: #fef9e7; border-left: 4px solid #f59e0b; border-radius: 8px; margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 20px; height: 20px; border: 3px solid #f59e0b; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                        <p style="margin: 0; color: #f59e0b; font-weight: 600;">Traitement en cours...</p>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="button" onclick="closeWithdrawalModal()" style="flex: 1; padding: 0.75rem; border: 2px solid #dee2e6; background: white; color: #333; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                        Annuler
                    </button>
                    <button type="submit" id="submitWithdrawal" style="flex: 1; padding: 0.75rem; border: none; background: #f59e0b; color: white; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.3s;">
                        <i class="fas fa-arrow-down"></i>
                        Retirer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de retrait PayPal -->
    <div id="paypalWithdrawalModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 16px; padding: 2rem; max-width: 500px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); max-height: 90vh; overflow-y: auto;">
            <h3 style="margin: 0 0 1rem 0; color: #0070ba; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fab fa-paypal"></i>
                Effectuer un retrait PayPal
            </h3>
            <p style="color: #6c757d; margin-bottom: 1.5rem;">Solde disponible: <strong id="paypalAvailableBalance">Chargement...</strong></p>

            <form id="paypalWithdrawalForm">
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 600;">Montant (USD)</label>
                    <input type="number" id="paypalAmount" name="amount" placeholder="Minimum 10 USD" min="10" step="0.01" required style="width: 100%; padding: 0.75rem; border: 2px solid #dee2e6; border-radius: 8px; font-size: 1rem;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 600;">Email PayPal</label>
                    <input type="email" id="paypalEmail" name="paypal_email" placeholder="votre-email@paypal.com" required style="width: 100%; padding: 0.75rem; border: 2px solid #dee2e6; border-radius: 8px; font-size: 1rem;">
                    <small style="color: #6c757d; font-size: 0.875rem;">L'adresse email du compte PayPal destinataire</small>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 600;">Notes (optionnel)</label>
                    <textarea id="paypalNotes" name="notes" rows="3" placeholder="Notes administratives..." style="width: 100%; padding: 0.75rem; border: 2px solid #dee2e6; border-radius: 8px; font-size: 1rem; resize: vertical;"></textarea>
                </div>

                <div id="paypalWithdrawalError" style="display: none; padding: 1rem; background: #fee; border-left: 4px solid #dc3545; border-radius: 8px; margin-bottom: 1rem;">
                    <p style="margin: 0; color: #dc3545;"></p>
                </div>

                <div id="paypalWithdrawalSuccess" style="display: none; padding: 1rem; background: #efe; border-left: 4px solid #28a745; border-radius: 8px; margin-bottom: 1rem;">
                    <p style="margin: 0; color: #28a745;"></p>
                </div>

                <div id="paypalWithdrawalProcessing" style="display: none; padding: 1rem; background: #e7f3ff; border-left: 4px solid #0070ba; border-radius: 8px; margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 20px; height: 20px; border: 3px solid #0070ba; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                        <p style="margin: 0; color: #0070ba; font-weight: 600;">Traitement en cours...</p>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="button" onclick="closePayPalWithdrawalModal()" style="flex: 1; padding: 0.75rem; border: 2px solid #dee2e6; background: white; color: #333; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                        Annuler
                    </button>
                    <button type="submit" id="submitPaypalWithdrawal" style="flex: 1; padding: 0.75rem; border: none; background: #0070ba; color: white; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.3s;">
                        <i class="fab fa-paypal"></i>
                        Retirer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Tab active styles */
        .tab-button[data-active="true"] {
            color: #1e293b !important;
            border-bottom-color: #667eea !important;
            background: white !important;
        }

        .tab-button:hover {
            background: rgba(102, 126, 234, 0.05);
        }

        /* Slide animation */
        .tab-content {
            animation: slideIn 0.3s ease-in-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>

    <script>
        // Tab switching
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.style.display = 'none';
            });

            // Remove active state from all buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.setAttribute('data-active', 'false');
            });

            // Show selected tab
            document.getElementById('tab-' + tabName).style.display = 'block';

            // Set active state
            document.querySelector(`[data-tab="${tabName}"]`).setAttribute('data-active', 'true');
        }

        // Initialize first tab
        document.addEventListener('DOMContentLoaded', function() {
            switchTab('revenus');
        });

        let pinVerified = false;
        let currentProvider = 'freemopay';

        function openWithdrawalModal(provider) {
            currentProvider = provider;
            if (!pinVerified) {
                openPinModal();
            } else {
                showWithdrawalForm(provider);
            }
        }

        function openPinModal() {
            document.getElementById('pinModal').style.display = 'flex';
            document.getElementById('pinInput').value = '';
            document.getElementById('pinError').style.display = 'none';
            document.getElementById('pinInput').focus();
        }

        function closePinModal() {
            document.getElementById('pinModal').style.display = 'none';
        }

        function verifyPin() {
            const pin = document.getElementById('pinInput').value;
            const errorEl = document.getElementById('pinError');

            if (!pin) {
                errorEl.textContent = 'Veuillez entrer votre code PIN';
                errorEl.style.display = 'block';
                return;
            }

            fetch('{{ route('admin.bank-account.verify-pin') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ pin: pin })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    pinVerified = true;
                    closePinModal();
                    showWithdrawalForm(currentProvider);
                } else {
                    errorEl.textContent = data.message || 'Code PIN incorrect';
                    errorEl.style.display = 'block';
                }
            })
            .catch(error => {
                errorEl.textContent = 'Erreur de connexion';
                errorEl.style.display = 'block';
            });
        }

        function showWithdrawalForm(provider) {
            if (provider === 'paypal') {
                document.getElementById('paypalWithdrawalModal').style.display = 'flex';

                // Fetch available PayPal balance
                fetch('{{ route('admin.bank-account.paypal-available-balance') }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('paypalAvailableBalance').textContent = new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: 'USD'
                            }).format(data.available_balance);
                        } else {
                            document.getElementById('paypalAvailableBalance').textContent = 'Erreur de chargement';
                        }
                    })
                    .catch(error => {
                        document.getElementById('paypalAvailableBalance').textContent = 'Erreur de chargement';
                    });
            } else {
                document.getElementById('withdrawalModal').style.display = 'flex';

                // Fetch available FreeMoPay balance
                fetch('{{ route('admin.bank-account.available-balance') }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('availableBalance').textContent = new Intl.NumberFormat('fr-FR').format(data.available_balance) + ' XAF';
                        } else {
                            document.getElementById('availableBalance').textContent = 'Erreur de chargement';
                        }
                    })
                    .catch(error => {
                        document.getElementById('availableBalance').textContent = 'Erreur de chargement';
                    });
            }
        }

        function closeWithdrawalModal() {
            document.getElementById('withdrawalModal').style.display = 'none';
            document.getElementById('withdrawalForm').reset();
            document.getElementById('withdrawalError').style.display = 'none';
            document.getElementById('withdrawalSuccess').style.display = 'none';
            document.getElementById('withdrawalProcessing').style.display = 'none';
        }

        document.getElementById('withdrawalForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitWithdrawal');
            const processingEl = document.getElementById('withdrawalProcessing');
            const errorEl = document.getElementById('withdrawalError');
            const successEl = document.getElementById('withdrawalSuccess');

            // Hide messages
            errorEl.style.display = 'none';
            successEl.style.display = 'none';
            processingEl.style.display = 'block';
            submitBtn.disabled = true;

            const formData = {
                amount: document.getElementById('amount').value,
                payment_method: document.getElementById('paymentMethod').value,
                phone: document.getElementById('phone').value,
                notes: document.getElementById('notes').value
            };

            fetch('{{ route('admin.bank-account.initiate-withdrawal') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                processingEl.style.display = 'none';
                submitBtn.disabled = false;

                if (data.success) {
                    successEl.querySelector('p').textContent = data.message || 'Retrait effectué avec succès !';
                    successEl.style.display = 'block';

                    setTimeout(() => {
                        closeWithdrawalModal();
                        location.reload();
                    }, 2000);
                } else {
                    errorEl.querySelector('p').textContent = data.message || 'Une erreur est survenue';
                    errorEl.style.display = 'block';
                }
            })
            .catch(error => {
                processingEl.style.display = 'none';
                submitBtn.disabled = false;
                errorEl.querySelector('p').textContent = 'Erreur de connexion. Veuillez réessayer.';
                errorEl.style.display = 'block';
            });
        });

        // Allow Enter key to submit PIN
        document.getElementById('pinInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                verifyPin();
            }
        });

        // PayPal withdrawal modal functions
        function closePayPalWithdrawalModal() {
            document.getElementById('paypalWithdrawalModal').style.display = 'none';
            document.getElementById('paypalWithdrawalForm').reset();
            document.getElementById('paypalWithdrawalError').style.display = 'none';
            document.getElementById('paypalWithdrawalSuccess').style.display = 'none';
            document.getElementById('paypalWithdrawalProcessing').style.display = 'none';
        }

        document.getElementById('paypalWithdrawalForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitPaypalWithdrawal');
            const processingEl = document.getElementById('paypalWithdrawalProcessing');
            const errorEl = document.getElementById('paypalWithdrawalError');
            const successEl = document.getElementById('paypalWithdrawalSuccess');

            // Hide messages
            errorEl.style.display = 'none';
            successEl.style.display = 'none';
            processingEl.style.display = 'block';
            submitBtn.disabled = true;

            const formData = {
                amount: document.getElementById('paypalAmount').value,
                paypal_email: document.getElementById('paypalEmail').value,
                notes: document.getElementById('paypalNotes').value
            };

            fetch('{{ route('admin.bank-account.initiate-paypal-withdrawal') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                processingEl.style.display = 'none';
                submitBtn.disabled = false;

                if (data.success) {
                    successEl.querySelector('p').textContent = data.message || 'Retrait PayPal effectué avec succès !';
                    successEl.style.display = 'block';

                    setTimeout(() => {
                        closePayPalWithdrawalModal();
                        location.reload();
                    }, 2000);
                } else {
                    errorEl.querySelector('p').textContent = data.message || 'Une erreur est survenue';
                    errorEl.style.display = 'block';
                }
            })
            .catch(error => {
                processingEl.style.display = 'none';
                submitBtn.disabled = false;
                errorEl.querySelector('p').textContent = 'Erreur de connexion. Veuillez réessayer.';
                errorEl.style.display = 'block';
            });
        });
    </script>
@endsection
