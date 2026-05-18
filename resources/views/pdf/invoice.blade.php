<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { color: #212121; font-size: 12px; margin: 0; padding: 32px; }
        .header { width: 100%; border-bottom: 3px solid #059669; padding-bottom: 16px; }
        .header td { vertical-align: top; }
        .brand { font-size: 22px; font-weight: bold; color: #059669; }
        .doc-title { font-size: 26px; font-weight: bold; text-align: right; color: #212121; }
        .meta { text-align: right; color: #757575; font-size: 11px; }
        .section { margin-top: 28px; }
        .parties { width: 100%; margin-top: 24px; }
        .parties td { width: 50%; vertical-align: top; padding-right: 16px; }
        .label { color: #757575; font-size: 10px; text-transform: uppercase; letter-spacing: .5px; }
        .value { font-size: 13px; font-weight: bold; margin-top: 2px; }
        .muted { color: #616161; font-size: 11px; margin-top: 2px; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 28px; }
        table.items th {
            background: #059669; color: #ffffff; text-align: left;
            padding: 10px 12px; font-size: 11px;
        }
        table.items td { padding: 12px; border-bottom: 1px solid #E0E0E0; }
        table.items td.num { text-align: right; }
        .totals { width: 100%; margin-top: 18px; }
        .totals td { padding: 6px 12px; }
        .totals .grand {
            font-size: 16px; font-weight: bold; color: #059669;
            border-top: 2px solid #059669;
        }
        .badge {
            display: inline-block; padding: 4px 12px; border-radius: 12px;
            background: #4CAF50; color: #fff; font-size: 11px; font-weight: bold;
        }
        .footer {
            margin-top: 48px; border-top: 1px solid #E0E0E0; padding-top: 12px;
            color: #9E9E9E; font-size: 10px; text-align: center;
        }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td>
                <div class="brand">{{ $seller_name }}</div>
                <div class="muted">{{ $seller_contact }}</div>
            </td>
            <td>
                <div class="doc-title">FACTURE</div>
                <div class="meta">
                    N&deg; {{ $invoice_number }}<br>
                    Date : {{ $date }}<br>
                    <span class="badge">{{ $status_label }}</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="parties">
        <tr>
            <td>
                <div class="label">Vendeur</div>
                <div class="value">{{ $seller_name }}</div>
                <div class="muted">{{ $seller_contact }}</div>
            </td>
            <td>
                <div class="label">Client</div>
                <div class="value">{{ $buyer_name }}</div>
                <div class="muted">{{ $buyer_contact }}</div>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th style="width:55%">Désignation</th>
                <th style="width:15%">Type</th>
                <th style="width:30%; text-align:right">Montant</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $product_name }}</strong>
                    @if($category)
                        <div class="muted">{{ $category }}</div>
                    @endif
                </td>
                <td>{{ $product_type }}</td>
                <td class="num">{{ $amount }} {{ $currency }}</td>
            </tr>
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td style="width:70%"></td>
            <td class="label">Sous-total</td>
            <td class="num">{{ $amount }} {{ $currency }}</td>
        </tr>
        <tr>
            <td></td>
            <td class="grand">Total payé</td>
            <td class="num grand">{{ $amount }} {{ $currency }}</td>
        </tr>
    </table>

    <div class="section">
        <span class="label">Mode de paiement</span>
        <div class="muted">{{ $payment_method }} — Wallet Estuaire Emploi</div>
    </div>

    <div class="footer">
        Facture générée automatiquement par Estuaire Emploi le {{ $date }}.<br>
        Ce document atteste du paiement effectué via le wallet.
    </div>
</body>
</html>
