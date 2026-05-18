<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyProduct;
use App\Models\CompanyProductPurchase;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Services\WalletService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CompanyProductController extends Controller
{
    /**
     * Get all products/services for a company
     */
    public function index(Request $request, $companyId)
    {
        try {
            $company = Company::findOrFail($companyId);

            // Charger le secteur (N1>N2>N3) du produit et l'entreprise
            $query = $company->products()->with(['category', 'company']);

            // Filter by type (product or service)
            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            // Filter by active status
            if ($request->has('is_active')) {
                $query->where('is_active', $request->is_active);
            }

            // Search by name
            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $products = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des produits/services',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store multiple products/services
     */
    public function storeMultiple(Request $request)
    {
        try {
            // Get authenticated user
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié',
                ], 401);
            }

            // Get user's company
            $company = $user->recruiter ? $user->recruiter->company : null;

            if (!$company) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune entreprise associée à cet utilisateur',
                ], 404);
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'products' => 'required|array|min:1',
                'products.*.name' => 'required|string|max:255',
                'products.*.description' => 'required|string|min:10',
                'products.*.type' => 'required|in:product,service',
                'products.*.billing_type' => 'required|in:fixed_price,to_discover,to_visit',
                'products.*.price' => 'required_if:products.*.billing_type,fixed_price|nullable|numeric|min:0',
                'products.*.currency' => 'required_if:products.*.billing_type,fixed_price|nullable|string|size:3|exists:currencies,code',
                'products.*.company_category_id' => 'nullable|exists:company_categories,id',
                'products.*.images' => 'required|array|min:2|max:4',
                'products.*.images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'products.*.stock' => 'nullable|integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation échouée',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $createdProducts = [];

            // Process each product/service
            foreach ($request->products as $productData) {
                // Handle image uploads
                $imagePaths = [];

                if (isset($productData['images']) && is_array($productData['images'])) {
                    foreach ($productData['images'] as $image) {
                        if ($image instanceof \Illuminate\Http\UploadedFile) {
                            $filename = Str::uuid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                            $path = $image->storeAs('company_products', $filename, 'public');
                            $imagePaths[] = $path;
                        }
                    }
                }

                // En mode "à découvrir" / "à visiter", pas de prix ni de devise
                $billingType = $productData['billing_type'] ?? 'fixed_price';
                $isFixedPrice = $billingType === 'fixed_price';

                // Create product/service
                $product = CompanyProduct::create([
                    'company_id' => $company->id,
                    'name' => $productData['name'],
                    'description' => $productData['description'],
                    'price' => $isFixedPrice ? ($productData['price'] ?? null) : null,
                    'billing_type' => $billingType,
                    'currency' => $isFixedPrice && isset($productData['currency'])
                        ? strtoupper($productData['currency'])
                        : null,
                    'company_category_id' => $productData['company_category_id'] ?? null,
                    'type' => $productData['type'],
                    'images' => $imagePaths,
                    'stock' => $productData['stock'] ?? null,
                    'is_active' => true,
                ]);

                $createdProducts[] = $product;
            }

            return response()->json([
                'success' => true,
                'message' => count($createdProducts) . ' produit(s)/service(s) ajouté(s) avec succès',
                'data' => $createdProducts,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout des produits/services',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a single product/service
     */
    public function store(Request $request)
    {
        try {
            // Get authenticated user
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié',
                ], 401);
            }

            // Get user's company
            $company = $user->recruiter ? $user->recruiter->company : null;

            if (!$company) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune entreprise associée à cet utilisateur',
                ], 404);
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'required|string|min:10',
                'type' => 'required|in:product,service',
                'billing_type' => 'required|in:fixed_price,to_discover,to_visit',
                // price et currency requis uniquement en mode prix fixe
                'price' => 'required_if:billing_type,fixed_price|nullable|numeric|min:0',
                'currency' => 'required_if:billing_type,fixed_price|nullable|string|size:3|exists:currencies,code',
                'company_category_id' => 'nullable|exists:company_categories,id',
                'images' => 'required|array|min:2|max:4',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'stock' => 'nullable|integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation échouée',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Handle image uploads
            $imagePaths = [];

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = Str::uuid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('company_products', $filename, 'public');
                    $imagePaths[] = $path;
                }
            }

            // En mode "à découvrir" / "à visiter", pas de prix ni de devise
            $isFixedPrice = $request->billing_type === 'fixed_price';

            // Create product/service
            $product = CompanyProduct::create([
                'company_id' => $company->id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $isFixedPrice ? $request->price : null,
                'billing_type' => $request->billing_type,
                'currency' => $isFixedPrice ? strtoupper($request->currency) : null,
                'company_category_id' => $request->company_category_id,
                'type' => $request->type,
                'images' => $imagePaths,
                'stock' => $request->stock ?? null,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Produit/service créé avec succès',
                'data' => $product,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du produit/service',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a single product/service
     */
    public function show($id)
    {
        try {
            $product = CompanyProduct::with(['company', 'category'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Produit/service non trouvé',
            ], 404);
        }
    }

    /**
     * Update a product/service
     */
    public function update(Request $request, $id)
    {
        try {
            $product = CompanyProduct::findOrFail($id);

            // Check if user owns this product
            $user = Auth::user();
            $company = $user->recruiter ? $user->recruiter->company : null;

            if (!$company || $product->company_id !== $company->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non autorisé',
                ], 403);
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string|min:10',
                'type' => 'sometimes|required|in:product,service',
                'billing_type' => 'sometimes|required|in:fixed_price,to_discover,to_visit',
                'price' => 'sometimes|nullable|numeric|min:0',
                'currency' => 'sometimes|nullable|string|size:3|exists:currencies,code',
                'company_category_id' => 'sometimes|nullable|exists:company_categories,id',
                'images' => 'sometimes|array|min:2|max:4',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'stock' => 'nullable|integer|min:0',
                'is_active' => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation échouée',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Handle image uploads if new images are provided
            if ($request->hasFile('images')) {
                // Delete old images
                if ($product->images) {
                    foreach ($product->images as $oldImage) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }

                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $filename = Str::uuid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('company_products', $filename, 'public');
                    $imagePaths[] = $path;
                }
                $product->images = $imagePaths;
            }

            // Update other fields
            $product->fill($request->only([
                'name',
                'description',
                'price',
                'billing_type',
                'currency',
                'company_category_id',
                'type',
                'stock',
                'is_active',
            ]));

            // Cohérence prix/devise selon le mode de facturation final
            if ($product->billing_type === 'fixed_price') {
                $product->currency = $product->currency
                    ? strtoupper($product->currency)
                    : $product->currency;
            } else {
                // "à découvrir" / "à visiter" : pas de prix ni de devise
                $product->price = null;
                $product->currency = null;
            }

            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Produit/service mis à jour avec succès',
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a product/service
     */
    public function destroy($id)
    {
        try {
            $product = CompanyProduct::findOrFail($id);

            // Check if user owns this product
            $user = Auth::user();
            $company = $user->recruiter ? $user->recruiter->company : null;

            if (!$company || $product->company_id !== $company->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non autorisé',
                ], 403);
            }

            // Delete images from storage
            if ($product->images) {
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produit/service supprimé avec succès',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Résout le user recruteur propriétaire d'une entreprise.
     * Le revenu des ventes va à ce user (les entreprises n'ont pas de wallet).
     */
    private function resolveCompanyOwner(Company $company): ?User
    {
        $recruiter = $company->recruiters()->orderBy('id')->first();
        return $recruiter ? $recruiter->user : null;
    }

    /**
     * Acheter un produit/service via le wallet.
     * POST /api/company-products/{id}/purchase
     */
    public function purchase(Request $request, $id)
    {
        try {
            $buyer = Auth::user();
            if (!$buyer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié',
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'provider' => 'required|in:freemopay,paypal',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation échouée',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $product = CompanyProduct::with('company')->findOrFail($id);

            // Seuls les produits à prix fixe sont achetables
            if ($product->billing_type !== 'fixed_price' || $product->price === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce produit/service n\'est pas disponible à l\'achat direct. Contactez l\'entreprise.',
                ], 422);
            }

            if (!$product->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce produit/service n\'est plus disponible',
                ], 422);
            }

            $seller = $this->resolveCompanyOwner($product->company);
            if (!$seller) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de trouver le bénéficiaire pour cette entreprise',
                ], 422);
            }

            if ($seller->id === $buyer->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas acheter votre propre produit',
                ], 422);
            }

            $amount = (float) $product->price;
            $provider = $request->provider;
            $walletService = app(WalletService::class);

            // Vérifier le solde de l'acheteur sur le provider choisi
            $canPay = $walletService->canPayWithWallet($buyer, $amount, $provider);
            if (!($canPay['can_pay'] ?? false)) {
                return response()->json([
                    'success' => false,
                    'message' => $canPay['message']
                        ?? 'Solde insuffisant pour effectuer cet achat',
                    'data' => $canPay,
                ], 422);
            }

            $purchase = DB::transaction(function () use (
                $buyer, $seller, $product, $amount, $provider, $walletService
            ) {
                // Débit acheteur / crédit recruteur propriétaire
                $result = $walletService->transfer(
                    $buyer,
                    $seller,
                    $amount,
                    $provider,
                    "Achat: {$product->name}"
                );

                return CompanyProductPurchase::create([
                    'company_product_id' => $product->id,
                    'company_id' => $product->company_id,
                    'buyer_user_id' => $buyer->id,
                    'seller_user_id' => $seller->id,
                    'amount' => $amount,
                    'currency' => $product->currency,
                    'provider' => $provider,
                    'status' => 'paid',
                    'invoice_number' => 'FAC-' . now()->format('Ymd') . '-'
                        . strtoupper(Str::random(6)),
                    'wallet_transaction_id' => $result['sender_transaction']->id ?? null,
                    'product_snapshot' => [
                        'name' => $product->name,
                        'price' => $amount,
                        'currency' => $product->currency,
                        'type' => $product->type,
                    ],
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Achat effectué avec succès',
                'data' => [
                    'purchase_id' => $purchase->id,
                    'amount' => $amount,
                    'currency' => $product->currency,
                    'status' => $purchase->status,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'achat',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ouvrir/réutiliser une conversation avec l'entreprise au sujet d'un
     * produit/service (le produit est taggé sur le message initial).
     * POST /api/company-products/{id}/inquiry
     */
    public function inquiry(Request $request, $id)
    {
        try {
            $buyer = Auth::user();
            if (!$buyer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié',
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'message' => 'nullable|string|max:1000',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation échouée',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $product = CompanyProduct::with('company')->findOrFail($id);
            $seller = $this->resolveCompanyOwner($product->company);

            if (!$seller) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette entreprise n\'a pas de contact disponible',
                ], 422);
            }

            if ($seller->id === $buyer->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas discuter avec vous-même',
                ], 422);
            }

            $currentUserId = $buyer->id;
            $providerId = $seller->id;

            // Réutiliser une conversation non liée à une application
            $conversation = Conversation::whereNull('application_id')
                ->where(function ($q) use ($currentUserId, $providerId) {
                    $q->where(function ($query) use ($currentUserId, $providerId) {
                        $query->where('user_one', $currentUserId)
                            ->where('user_two', $providerId);
                    })->orWhere(function ($query) use ($currentUserId, $providerId) {
                        $query->where('user_one', $providerId)
                            ->where('user_two', $currentUserId);
                    });
                })
                ->first();

            if (!$conversation) {
                $conversation = Conversation::create([
                    'application_id' => null,
                    'user_one' => $currentUserId,
                    'user_two' => $providerId,
                    'service_id' => null,
                ]);
            }

            // Message initial taggé avec le produit
            $text = $request->filled('message')
                ? $request->message
                : "Bonjour, je suis intéressé(e) par : {$product->name}";

            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $currentUserId,
                'message' => $text,
                'status' => 'sent',
                'company_product_id' => $product->id,
                'metadata' => [
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'currency' => $product->currency,
                        'billing_type' => $product->billing_type,
                        'type' => $product->type,
                        'image' => $product->first_image_url,
                    ],
                ],
            ]);

            try {
                broadcast(new \App\Events\MessageSent($message))->toOthers();
            } catch (\Exception $e) {
                \Log::warning('inquiry broadcast failed: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Conversation ouverte',
                'data' => [
                    'conversation_id' => $conversation->id,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ouverture de la conversation',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Génère (si besoin) et renvoie l'URL de la facture PDF d'un achat.
     * GET /api/company-product-purchases/{id}/invoice
     */
    public function invoice($id)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié',
                ], 401);
            }

            $purchase = CompanyProductPurchase::with([
                'product.category', 'company', 'buyer', 'seller',
            ])->findOrFail($id);

            // Seul l'acheteur (ou le vendeur) peut récupérer la facture
            if ($purchase->buyer_user_id !== $user->id
                && $purchase->seller_user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non autorisé',
                ], 403);
            }

            // N° de facture (sécurité si absent sur d'anciens achats)
            if (empty($purchase->invoice_number)) {
                $purchase->invoice_number = 'FAC-' . $purchase->created_at->format('Ymd')
                    . '-' . strtoupper(Str::random(6));
                $purchase->save();
            }

            // Génération à la volée et stream direct du PDF (pas de
            // stockage disque -> pas de souci de permission/symlink/404).
            $snapshot = $purchase->product_snapshot ?? [];
            $product = $purchase->product;
            $category = $product && $product->category
                ? trim(implode(' › ', array_filter([
                    $product->category->level_1,
                    $product->category->level_2,
                    $product->category->level_3,
                ])))
                : null;

            $data = [
                'invoice_number' => $purchase->invoice_number,
                'date' => $purchase->created_at->format('d/m/Y H:i'),
                'status_label' => $purchase->status === 'paid' ? 'PAYÉE' : 'EN ATTENTE',
                'seller_name' => $purchase->company->name ?? 'Entreprise',
                'seller_contact' => trim(
                    ($purchase->company->email ?? '') . ' '
                    . ($purchase->company->phone ?? '')
                ),
                'buyer_name' => $purchase->buyer->name ?? 'Client',
                'buyer_contact' => trim(
                    ($purchase->buyer->email ?? '') . ' '
                    . ($purchase->buyer->phone ?? '')
                ),
                'product_name' => $snapshot['name'] ?? ($product->name ?? 'Article'),
                'product_type' => ($snapshot['type'] ?? $product->type ?? 'product') === 'service'
                    ? 'Service' : 'Produit',
                'category' => $category,
                'amount' => number_format((float) $purchase->amount, 0, ',', ' '),
                'currency' => $purchase->currency ?? '',
                'payment_method' => $purchase->provider === 'paypal'
                    ? 'PayPal' : 'Mobile Money (FreeMoPay)',
            ];

            $pdf = Pdf::loadView('pdf.invoice', $data)
                ->setPaper('a4', 'portrait')
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isRemoteEnabled', true);

            $filename = $purchase->invoice_number . '.pdf';

            // Stream le PDF directement dans la réponse HTTP.
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération de la facture',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
