<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => function () {
                return Client::factory()->create()->clerk_id;
            },
            'session_id' => $this->faker->uuid,
            'customer_name' => $this->faker->name,
            'payment_status' => $this->faker->boolean,
            'total_amount' => $this->faker->randomFloat(2, 0, 1000),
            'payment_method' => $this->faker->randomElement(['credit card', 'PayPal', 'cash on delivery']),
            // 'shipping_address' => [
            //     'address' => $this->faker->streetAddress,
            //     'city' => $this->faker->city,
            //     'state' => $this->faker->state,
            //     'zipcode' => $this->faker->postcode,
            // ],
            // 'billing_address' => [
            //     'address' => $this->faker->streetAddress,
            //     'city' => $this->faker->city,
            //     'state' => $this->faker->state,
            //     'zipcode' => $this->faker->postcode,
            // ],
            'discount_amount' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
