<?php

namespace Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Models\User;
use Database\Factories\UserFactory;
use Faker\Factory;
use Illuminate\Support\Str;
use Psy\Util\Json;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function testUserCannotLoginWithWrongCredentials()
    {
        $user = User::factory()->create();

        $request = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => '539680928695028695'
        ]);

        User::destroy($user->id);

        $request->assertStatus(401);
        $request->assertJsonStructure([
            'status',
            'error',
            'data' => []
        ]);
    }

    public function testUserCannotLoginWithInvalidEmail()
    {
        $request = $this->post('/api/auth/login', [
            'email' => Str::uuid(),
            'password' => '12345678'
        ]);

        $request->assertStatus(302);
    }

    public function testUserMustLoginWithRightCredentials()
    {
        $user = User::factory()->create();

        $request = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => '12345678'
        ]);

        User::destroy($user->id);

        $request->assertStatus(200);
        $request->assertJsonStructure([
            'status',
            'error',
            'data' => []
        ]);
    }

    public function testUserCannotRegisterWithInvalidRequestParameters()
    {
        $payload = [
            'name',
            'age',
            'email',
            'password',
            'phone',
            'locale',
        ];

        $request = $this->post('/api/auth/register', $payload);

        $request->assertStatus(302);
    }

    public function testUserMustRegisterWithRightCredentials()
    {
        $faker = Factory::create();

        $user = [
            'name' => $faker->name,
            'age' => $faker->randomNumber(3),
            'email' => $faker->safeEmail,
            'password' => '1234',
            'password_confirmation' => '1234',
            'phone' => $faker->phoneNumber,
            'locale' => $faker->country,
        ];

        $request = $this->post('/api/auth/register', $user);

        $request->assertStatus(200);
        $request->assertJsonStructure([
            'status',
            'error',
            'data' => []
        ]);

        $user = User::latest()->first();
        User::destroy($user->id);
    }
}
