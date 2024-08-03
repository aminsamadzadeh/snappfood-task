<?php

namespace Tests\Feature;

use App\Models\DelayReport;
use App\Models\Order;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DelayReportTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_order_is_not_late(): void
    {
        $order = Order::factory()->create();
        DelayReport::factory()->create(['order_id' => $order->id]);

        $response = $this->post("/api/orders/{$order->id}/delay-report");

        $response
            ->assertStatus(400)
            ->assertJson(['message' => 'order is not late']);
    }

    public function test_order_has_open_delay_report(): void
    {
        $order = Order::factory()->create(['delivery_time' => Carbon::now()->subMinutes(10)]);
        DelayReport::factory()->create(['order_id' => $order->id]);

        $response = $this->post("/api/orders/{$order->id}/delay-report");

        $response
            ->assertStatus(400)
            ->assertJson(['message' => 'order has open delay report']);
    }

    public function test_order_has_ongoing_trip(): void
    {
        $order = Order::factory()->create(['delivery_time' => Carbon::now()->subMinutes(10)]);
        Trip::factory()->create(['order_id' => $order->id]);

        $response = $this->post("/api/orders/{$order->id}/delay-report");

        $response->assertStatus(200);
        $this->assertEquals($response->json()['message'], 'order has new delivery time'); 
        $this->assertTrue(DelayReport::where('order_id', $order->id)->exists());
    }

    public function test_order_has_not_ongoing_trip(): void
    {
        $order = Order::factory()->create(['delivery_time' => Carbon::now()->subMinutes(10)]);
        Trip::factory()->create(['order_id' => $order->id, 'state' => 'delivered']);

        $response = $this->post("/api/orders/{$order->id}/delay-report");
        
        $response->assertStatus(200);
        $this->assertTrue(DelayReport::where('order_id', $order->id)->exists());
    }
    
    public function test_analyse(): void
    {
        $a = DelayReport::factory(10)->create();
        
        $response = $this->get("/api/delay-report/analyse");

        $response
            ->assertStatus(200)
            ->assertJsonIsArray()
            ->assertJsonStructure(['*' => ['delay_minute_sum']]);
    }
}
