<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TravelOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelOrderApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ==========================
     * AUTENTICAÇÃO
     * ==========================
     */

    /** Usuário sem token não pode acessar a listagem */
    public function test_guest_cannot_list_travel_orders()
    {
        $this->getJson('/api/travel-orders')
            ->assertStatus(401);
    }

    /**
     * ==========================
     * CRIAÇÃO DE PEDIDOS
     * ==========================
     */

    /** Usuário autenticado pode criar pedido com dados válidos */
    public function test_user_can_create_travel_order()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api')
            ->postJson('/api/travel-orders', [
                'destination'     => 'São Paulo',
                'departure_date'  => '2025-10-01',
                'return_date'     => '2025-10-05',
            ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'destination' => 'São Paulo',
                'status'      => 'solicitado'
            ]);

        $this->assertDatabaseHas('travel_orders', [
            'user_id'     => $user->id,
            'destination' => 'São Paulo',
        ]);
    }

    /** Não deve permitir criar pedido sem destino */
    public function test_cannot_create_travel_order_without_destination()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api')
            ->postJson('/api/travel-orders', [
                'departure_date' => '2025-10-01',
                'return_date'    => '2025-10-05',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('destination');
    }

    /** Não deve permitir criar pedido com datas inválidas */
    public function test_cannot_create_travel_order_with_invalid_dates()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api')
            ->postJson('/api/travel-orders', [
                'destination'    => 'Rio de Janeiro',
                'departure_date' => '2025-10-10',
                'return_date'    => '2025-10-01',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('return_date');
    }

    /**
     * ==========================
     * REGRAS DE NEGÓCIO
     * ==========================
     */

    /** Usuário comum NÃO pode alterar status do pedido */
    public function test_regular_user_cannot_update_order_status()
    {
        $user  = User::factory()->create();
        $order = TravelOrder::factory()->for($user)->create([
            'status' => 'solicitado'
        ]);

        $this->actingAs($user, 'api')
            ->patchJson("/api/travel-orders/{$order->id}/status", [
                'status' => 'aprovado'
            ])
            ->assertStatus(403);
    }

    /** Admin pode aprovar pedido */
    public function test_admin_can_approve_travel_order()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user  = User::factory()->create();

        $order = TravelOrder::factory()->for($user)->create([
            'status' => 'solicitado'
        ]);

        $this->actingAs($admin, 'api')
            ->patchJson("/api/travel-orders/{$order->id}/status", [
                'status' => 'aprovado'
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('travel_orders', [
            'id'     => $order->id,
            'status' => 'aprovado'
        ]);
    }

    /** Pedido aprovado NÃO pode ser cancelado */
    public function test_cannot_cancel_approved_travel_order()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user  = User::factory()->create();

        $order = TravelOrder::factory()->for($user)->create([
            'status' => 'aprovado'
        ]);

        dd($order->toArray());

        $this->actingAs($admin, 'api')
            ->patchJson("/api/travel-orders/{$order->id}/status", [
                'status' => 'cancelado'
            ])
            ->assertStatus(422);
    }
}
