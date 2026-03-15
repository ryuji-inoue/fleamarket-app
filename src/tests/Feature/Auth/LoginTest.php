<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_validation()
    {
        $response = $this->post('/login',[
            'email' => '',
            'password' => ''
        ]);

        $response->assertSessionHasErrors(['email','password']);
    }

    public function test_login_success()
    {
        $user = User::factory()->create([
            'password'=>bcrypt('password123')
        ]);

        $response = $this->post('/login',[
            'email'=>$user->email,
            'password'=>'password123'
        ]);

        $this->assertAuthenticated();
    }
}