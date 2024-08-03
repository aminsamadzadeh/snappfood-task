<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(['role' => 'user']),
            'vendor_id' => Vendor::factory(),
            'delivery_time' => Carbon::now()->addMinutes(random_int(30, 120))
        ];
    }

    public function withDeliveryTime($deliveryTime): static
    {
        return $this->state(fn (array $attributes) => [
            'delivery_time' => $deliveryTime,
        ]);
    }


}
