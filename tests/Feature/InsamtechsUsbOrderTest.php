<?php

namespace Tests\Feature;

use App\Models\InsamtechsUsbOrder;
use App\Models\Setting;
use App\Models\User;
use App\Services\InsamtechsCatalogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InsamtechsUsbOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mock(InsamtechsCatalogService::class)
            ->shouldReceive('catalog')
            ->andReturn([
                ['id' => 10, 'intitule' => ['fr' => 'Montage vidéo'], 'etat' => 1],
                ['id' => 20, 'intitule' => ['fr' => 'Programmation'], 'etat' => 1],
            ]);
    }

    public function test_order_uses_server_prices_and_keeps_requested_formations(): void
    {
        Setting::setMany([
            'insamtechs_usb_unit_price_xaf' => 5000,
            'insamtechs_usb_delivery_price_xaf' => 2000,
        ]);
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum')->postJson('/api/insamtechs-usb/orders', [
            'formation_ids' => [10, 20],
            'customer_name' => 'Alice Test',
            'phone' => '690000000',
            'city' => 'Douala',
            'address' => 'Bonapriso',
            'total_price_xaf' => 1,
        ])->assertCreated()->assertJsonPath('data.total_price_xaf', 12000)
            ->assertJsonPath('data.status', 'pending');

        $order = InsamtechsUsbOrder::firstOrFail();
        $this->assertSame([10, 20], array_column($order->formations, 'id'));
        $this->assertSame($user->id, $order->user_id);
    }

    public function test_order_requires_auth_valid_ids_and_configured_price(): void
    {
        $payload = [
            'formation_ids' => [10], 'customer_name' => 'Alice Test',
            'phone' => '690000000', 'city' => 'Douala', 'address' => 'Bonapriso',
        ];
        $this->postJson('/api/insamtechs-usb/orders', $payload)->assertUnauthorized();

        $this->actingAs(User::factory()->create(), 'sanctum');
        $this->postJson('/api/insamtechs-usb/orders', $payload)->assertUnprocessable();

        Setting::set('insamtechs_usb_unit_price_xaf', 5000);
        $this->postJson('/api/insamtechs-usb/orders', array_merge($payload, ['formation_ids' => [999]]))
            ->assertUnprocessable();
        $this->postJson('/api/insamtechs-usb/orders', array_merge($payload, ['formation_ids' => [10, 10]]))
            ->assertUnprocessable();
        $this->assertSame(0, InsamtechsUsbOrder::count());
    }

    public function test_super_admin_sees_order_and_customer_account(): void
    {
        $customer = User::factory()->create([
            'name' => 'Client USB',
            'email' => 'client-usb@example.test',
        ]);
        $admin = User::factory()->create(['role' => 'admin', 'is_super_admin' => true]);
        InsamtechsUsbOrder::create([
            'user_id' => $customer->id,
            'formations' => [['id' => 10, 'title' => 'Montage vidéo']],
            'customer_name' => 'Client USB',
            'phone' => '690000000',
            'city' => 'Douala',
            'address' => 'Bonapriso',
            'unit_price_xaf' => 5000,
            'delivery_price_xaf' => 2000,
            'total_price_xaf' => 7000,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('Commandes de formations sur clé USB')
            ->assertSee('client-usb@example.test');
        $this->get('/admin/insamtechs-usb')
            ->assertOk()
            ->assertSee('client-usb@example.test')
            ->assertSee('Voir tout le compte')
            ->assertSee('Bonapriso');
    }
}
