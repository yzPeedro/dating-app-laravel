<?php

namespace Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Support\Str;
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
}
