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
        $sex = ['male', 'female', 'other'];
        $sex_interest = ['male', 'female', 'all'];

        return [
            'id' => Str::uuid(),
            'name' => $this->faker->name(),
            'age' => $this->faker->randomNumber(3),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('12345678'),
            'bio' => $this->faker->text,
            'interests' => implode(',',[$this->faker->word, $this->faker->word, $this->faker->word]),
            'locale' => $this->faker->country,
            'phone' => $this->faker->phoneNumber,
            'sex' => $sex[array_rand($sex)],
            'sex_interest' => $sex_interest[array_rand($sex_interest)],
            'active' => $this->faker->boolean
        ];
    }
}
