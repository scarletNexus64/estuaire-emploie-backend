<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InsamtechsUsbOrder;
use App\Models\Setting;
use Illuminate\Http\Request;

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
        $data = $request->validate(['status' => 'required|in:pending,confirmed,shipped,delivered,cancelled']);
        $order->update($data);

        return back()->with('success', 'Statut de la commande mis à jour.');
    }
}
