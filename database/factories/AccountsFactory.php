<?php

namespace Database\Factories;

use App\Models\Accounts;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Accounts>
 */
class AccountsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'=>User::factory()->create(),
            'type'=>Accounts::TYPES[array_rand(Accounts::TYPES)],
            'currency'=>Accounts::CURRENCIES[array_rand(Accounts::CURRENCIES)],
            'name'=>$this->faker->name,
            'iban'=>$this->faker->iban,
            'balance'=>$this->faker->numberBetween(100, 1000),
        ];
    }
}
