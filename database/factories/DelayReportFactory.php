<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Order;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DelayReport>
 */
class DelayReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $order = Order::factory()->create();
        return [
            'user_id' => User::factory()->create(['role' => 'user']),
            'order_id' => $order->id,
            'delay_minute' => random_int(1, 120),
            'old_delivery_time' => $order->delivery_time,
        ];
    }
}
