@extends('admin.layouts.app')

@section('title', 'Commandes USB InsamTechs')
@section('page-title', 'Commandes USB InsamTechs')

@section('content')
<div class="card" style="margin-bottom: 1.5rem">
    <div class="card-body">
        <h3>Tarifs des formations sur clé USB</h3>
        <p>Le total affiché au client est le prix par formation multiplié par le nombre de formations, plus les frais de clé USB et de livraison. Aucun paiement n'est prélevé à la commande.</p>
        <form method="POST" action="{{ route('admin.insamtechs-usb.pricing') }}" style="display:flex;gap:1rem;align-items:end;flex-wrap:wrap">
            @csrf
            @method('PUT')
            <label>Prix par formation (XAF)<br><input class="form-input" type="number" name="unit_price_xaf" min="1" required value="{{ old('unit_price_xaf', $unitPrice) }}"></label>
            <label>Clé USB et livraison (XAF)<br><input class="form-input" type="number" name="delivery_price_xaf" min="0" required value="{{ old('delivery_price_xaf', $deliveryPrice) }}"></label>
            <button class="btn btn-primary" type="submit">Enregistrer</button>
        </form>
        @if($errors->any()) <p style="color:#b91c1c">{{ $errors->first() }}</p> @endif
    </div>
</div>

<div class="card"><div class="card-body">
    <h3>Commandes reçues</h3>
    <div style="overflow-x:auto"><table class="admin-table">
        <thead><tr><th>Commande</th><th>Client</th><th>Formations</th><th>Livraison</th><th>Total XAF</th><th>Statut</th></tr></thead>
        <tbody>
        @forelse($orders as $order)
            <tr>
                <td>#{{ $order->id }}<br>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <strong>{{ $order->customer_name }}</strong><br>
                    {{ $order->phone }}<br>
                    Compte #{{ $order->user_id }}<br>
                    {{ $order->user?->name ?? 'Compte supprimé' }}<br>
                    {{ $order->user?->email ?? '—' }}<br>
                    Téléphone du compte : {{ $order->user?->phone ?? '—' }}<br>
                    Rôle : {{ $order->user?->role ?? '—' }} · Pays : {{ $order->user?->country ?? '—' }}<br>
                    Inscrit le : {{ $order->user?->created_at?->format('d/m/Y') ?? '—' }}<br>
                    @if(auth()->user()?->isSuperAdmin() && $order->user)
                        <a href="{{ route('admin.users.show', $order->user) }}">Voir tout le compte</a>
                    @endif
                </td>
                <td><ul>@foreach($order->formations as $formation)<li>{{ $formation['title'] }} (#{{ $formation['id'] }})</li>@endforeach</ul></td>
                <td>{{ $order->address }}, {{ $order->city }}@if($order->notes)<br><small>{{ $order->notes }}</small>@endif</td>
                <td>{{ number_format($order->total_price_xaf, 0, ',', ' ') }}<br><small>{{ count($order->formations) }} × {{ number_format($order->unit_price_xaf, 0, ',', ' ') }} + {{ number_format($order->delivery_price_xaf, 0, ',', ' ') }} livraison</small></td>
                <td><form method="POST" action="{{ route('admin.insamtechs-usb.status', $order) }}">@csrf @method('PATCH')
                    <select class="form-input" name="status">
                        @foreach(['pending' => 'En attente', 'confirmed' => 'Confirmée', 'shipped' => 'Expédiée', 'delivered' => 'Livrée', 'cancelled' => 'Annulée'] as $value => $label)
                            <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                        @endforeach
                    </select><button class="btn btn-sm btn-primary" type="submit">Mettre à jour</button>
                </form></td>
            </tr>
        @empty
            <tr><td colspan="6">Aucune commande USB.</td></tr>
        @endforelse
        </tbody>
    </table></div>
    {{ $orders->links() }}
</div></div>
@endsection
