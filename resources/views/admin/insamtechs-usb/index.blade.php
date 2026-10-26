@extends('admin.layouts.app')

@section('title', 'Commandes USB InsamTechs')
@section('page-title', 'Commandes USB InsamTechs')

@push('styles')
<style>
.usb-panel { margin-bottom: 24px; }
.usb-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(min(100%,260px),1fr)); gap:24px; }
.usb-field { display:flex; flex-direction:column; gap:8px; margin-bottom:16px; }
.usb-field input,.usb-field select,.usb-field textarea { width:100%; padding:12px; border:1px solid #cbd5e1; border-radius:10px; box-sizing:border-box; }
.usb-note { padding:16px; border-radius:12px; background:#f0fdf4; border:1px solid #bbf7d0; white-space:pre-wrap; overflow-wrap:anywhere; }
.usb-muted { color:#64748b; font-size:14px; }
.usb-header { display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:20px; }
</style>
@endpush

@section('content')
<div class="card usb-panel"><div class="card-body">
    <h3>Tarifs des formations sur clé USB</h3>
    <p class="usb-muted" style="margin:8px 0 20px">Prix par formation + frais de clé USB et de livraison. Aucun paiement n'est prélevé à la commande.</p>
    <form method="POST" action="{{ route('admin.insamtechs-usb.pricing') }}">
        @csrf @method('PUT')
        <div class="usb-grid">
            <label class="usb-field">Prix par formation (XAF)
                <input type="number" name="unit_price_xaf" min="1" max="10000000" required value="{{ old('unit_price_xaf', $unitPrice) }}">
            </label>
            <label class="usb-field">Clé USB et livraison (XAF)
                <input type="number" name="delivery_price_xaf" min="0" max="10000000" required value="{{ old('delivery_price_xaf', $deliveryPrice) }}">
            </label>
        </div>
        <button class="btn btn-primary" type="submit">Enregistrer les tarifs</button>
    </form>
</div></div>
@if($errors->any())
    <div role="alert" class="card usb-panel" style="color:#b91c1c;padding:16px">{{ $errors->first() }}</div>
@endif
<h3 style="margin-bottom:16px">Commandes reçues ({{ $orders->total() }})</h3>
@forelse($orders as $order)
<div class="card usb-panel" id="order-{{ $order->id }}"><div class="card-body">
    <div class="usb-header">
        <div><h3>Commande #{{ $order->id }} — {{ $order->customer_name }}</h3><p class="usb-muted">Reçue le {{ $order->created_at->format('d/m/Y à H:i') }}</p></div>
        <strong>{{ \App\Models\InsamtechsUsbOrder::STATUS_LABELS[$order->status] ?? $order->status }}</strong>
    </div>
    <div class="usb-grid">
        <div>
            <h4>Client et livraison</h4>
            <p>{{ $order->phone }}</p>
            <p style="white-space:pre-wrap;overflow-wrap:anywhere">{{ $order->address }}, {{ $order->city }}</p>
            @if($order->notes)<p style="white-space:pre-wrap">Précisions : {{ $order->notes }}</p>@endif
            <p class="usb-muted" style="margin-top:12px">Compte #{{ $order->user_id }} · {{ $order->user?->name ?? 'Compte supprimé' }}<br>{{ $order->user?->email }}<br>Téléphone du compte : {{ $order->user?->phone ?? '—' }}</p>
            @if(auth()->user()?->isSuperAdmin() && $order->user)
                <a href="{{ route('admin.users.show', $order->user) }}">Voir tout le compte</a>
            @endif
        </div>
        <div>
            <h4>Formations commandées</h4>
            <ul>@foreach($order->formations as $formation)<li>{{ $formation['title'] }} (#{{ $formation['id'] }})</li>@endforeach</ul>
            <p style="margin-top:16px"><strong>{{ number_format($order->total_price_xaf, 0, ',', ' ') }} XAF</strong></p>
            <p class="usb-muted">{{ count($order->formations) }} × {{ number_format($order->unit_price_xaf, 0, ',', ' ') }} + {{ number_format($order->delivery_price_xaf, 0, ',', ' ') }} XAF de clé USB et livraison</p>
        </div>
        <div>
            @if($order->replied_at)
                <h4>Réponse envoyée au client</h4>
                <p class="usb-note">{{ $order->admin_reply }}</p>
                <p class="usb-muted" style="margin:8px 0 16px">Envoyée le {{ $order->replied_at->format('d/m/Y à H:i') }}. Vous pouvez encore mettre à jour le statut.</p>
            @endif
            <form method="POST" action="{{ route('admin.insamtechs-usb.status', $order) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <label class="usb-field">Statut de la commande
                    <select name="status" required>
                        @foreach(\App\Models\InsamtechsUsbOrder::STATUS_LABELS as $value => $label)
                            <option value="{{ $value }}" @selected((old('order_id') == $order->id ? old('status', $order->status) : $order->status) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                @unless($order->replied_at)
                    <label class="usb-field">Réponse au client (facultatif)
                        <textarea name="admin_reply" rows="4" maxlength="2000" placeholder="Précisez les modalités de livraison ou de retrait…">{{ old('order_id') == $order->id ? old('admin_reply') : '' }}</textarea>
                        <small class="usb-muted">Une seule réponse par commande. Elle sera conservée dans le suivi du client et ne pourra plus être modifiée.</small>
                    </label>
                @endunless
                <button class="btn btn-primary" type="submit">Enregistrer et notifier le client</button>
            </form>
        </div>
    </div>
</div></div>
@empty
<div class="card"><div class="card-body">Aucune commande USB reçue.</div></div>
@endforelse
{{ $orders->links() }}
@endsection
