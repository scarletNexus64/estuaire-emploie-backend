<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InsamtechsUsbOrder;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Services\UsbOrderNotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InsamtechsUsbOrderController extends Controller
{
    public function index()
    {
        return view('admin.insamtechs-usb.index', [
            'orders' => InsamtechsUsbOrder::with('user')->latest()->paginate(20),
            'unitPrice' => (int) Setting::get('insamtechs_usb_unit_price_xaf', 0),
            'deliveryPrice' => (int) Setting::get('insamtechs_usb_delivery_price_xaf', 0),
        ]);
    }

    public function updatePricing(Request $request)
    {
        $data = $request->validate([
            'unit_price_xaf' => 'required|integer|min:1|max:10000000',
            'delivery_price_xaf' => 'required|integer|min:0|max:10000000',
        ]);
        Setting::setMany([
            'insamtechs_usb_unit_price_xaf' => $data['unit_price_xaf'],
            'insamtechs_usb_delivery_price_xaf' => $data['delivery_price_xaf'],
        ]);

        return back()->with('success', 'Tarifs USB mis à jour.');
    }

    public function updateStatus(Request $request, InsamtechsUsbOrder $order)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,confirmed,shipped,delivered,cancelled',
            'admin_reply' => 'nullable|string|max:2000',
        ]);
        $reply = trim($data['admin_reply'] ?? '');

        $changed = DB::transaction(function () use ($request, $order, $data, $reply) {
            // Relire sous verrou empêche deux administrateurs de répondre à la
            // même commande, ou de notifier deux fois une même modification.
            $locked = InsamtechsUsbOrder::lockForUpdate()->findOrFail($order->id);
            if ($reply !== '' && $locked->replied_at !== null) {
                throw ValidationException::withMessages([
                    'admin_reply' => 'Une réponse a déjà été envoyée pour cette commande.',
                ]);
            }
            if ($locked->status === $data['status'] && $reply === '') {
                return false;
            }

            $locked->status = $data['status'];
            if ($reply !== '') {
                $locked->admin_reply = $reply;
                $locked->replied_at = now();
                $locked->replied_by = $request->user()->id;
            }
            $locked->save();

            $label = InsamtechsUsbOrder::STATUS_LABELS[$locked->status];
            app(UsbOrderNotificationService::class)->record(
                $locked,
                $reply !== '' ? 'usb_order_reply' : 'usb_order_status_changed',
                $reply !== '' ? "Réponse à votre commande USB n°{$locked->id}" : "Commande USB n°{$locked->id} : {$label}",
                $reply !== '' ? $reply : "Votre commande USB n°{$locked->id} est maintenant : {$label}.",
            );

            return true;
        });

        return back()->with('success', $changed
            ? 'Commande mise à jour. La notification est disponible dans le compte du client.'
            : 'Aucune modification. Aucune nouvelle notification envoyée.');
    }
}
