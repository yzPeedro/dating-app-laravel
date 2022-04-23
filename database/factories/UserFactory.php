<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'name' => $this->faker->name(),
            'age' => $this->faker->randomNumber(3),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('12345678'),
            'bio' => $this->faker->text,
            'interests' => implode(',', ['games', 'sports', 'programming']),
            'locale' => $this->faker->country,
            'phone' => $this->faker->phoneNumber,
            'sex' => 'male',
            'sex_interest' => 'male'
        ];
    }
}
