<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InsamtechsUsbOrder;
use App\Models\Setting;
use App\Services\InsamtechsCatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InsamtechsUsbOrderController extends Controller
{
    public function pricing()
    {
        $unit = (int) Setting::get('insamtechs_usb_unit_price_xaf', 0);
        $delivery = (int) Setting::get('insamtechs_usb_delivery_price_xaf', 0);

        return response()->json(['success' => true, 'data' => [
            'available' => $unit > 0,
            'unit_price_xaf' => $unit,
            'delivery_price_xaf' => $delivery,
            'currency' => 'XAF',
        ]]);
    }

    public function store(Request $request, InsamtechsCatalogService $catalog)
    {
        $data = $request->validate([
            'formation_ids' => 'required|array|min:1|max:100',
            'formation_ids.*' => 'required|integer|min:1|distinct',
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ]);

        $items = collect($catalog->catalog())->keyBy('id');
        if ($items->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Catalogue temporairement indisponible. Réessayez plus tard.'], 503);
        }

        $formations = [];
        foreach ($data['formation_ids'] as $id) {
            $item = $items->get($id);
            if (! $item || ! ($item['etat'] ?? true)) {
                throw ValidationException::withMessages(['formation_ids' => 'Une formation sélectionnée est indisponible.']);
            }
            $formations[] = [
                'id' => (int) $id,
                'title' => InsamtechsCatalogService::text($item['intitule'] ?? ''),
            ];
        }

        $order = DB::transaction(function () use ($request, $data, $formations) {
            // Le tarif est relu côté serveur au moment de la commande.
            $unit = (int) Setting::get('insamtechs_usb_unit_price_xaf', 0);
            $delivery = (int) Setting::get('insamtechs_usb_delivery_price_xaf', 0);
            if ($unit <= 0) {
                throw ValidationException::withMessages(['formation_ids' => 'La commande USB est indisponible actuellement.']);
            }

            return InsamtechsUsbOrder::create([
                'user_id' => $request->user()->id,
                'formations' => $formations,
                'customer_name' => $data['customer_name'],
                'phone' => $data['phone'],
                'city' => $data['city'],
                'address' => $data['address'],
                'notes' => $data['notes'] ?? null,
                'unit_price_xaf' => $unit,
                'delivery_price_xaf' => $delivery,
                'total_price_xaf' => $unit * count($formations) + $delivery,
                'status' => 'pending',
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Commande USB enregistrée.', 'data' => $order], 201);
    }

    public function index(Request $request)
    {
        return response()->json(['success' => true, 'data' => InsamtechsUsbOrder::where('user_id', $request->user()->id)->latest()->paginate(20)]);
    }
}
