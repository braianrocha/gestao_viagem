<?php

namespace Database\Factories;

use App\Models\TravelOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelOrderFactory extends Factory
{
    protected $model = TravelOrder::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'destination' => $this->faker->city(),
            'departure_date' => now()->addDays(5),
            'return_date' => now()->addDays(10),
            'status' => 'solicitado',
        ];
    }
}
