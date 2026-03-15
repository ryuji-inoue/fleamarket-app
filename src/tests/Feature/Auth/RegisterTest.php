<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_name_is_required()
    {
        $response = $this->post('/register',[
            'name' => '',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_email_is_required()
    {
        $response = $this->post('/register',[
            'name' => 'test',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_password_must_be_at_least_8_characters()
    {
        $response = $this->post('/register',[
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => '1234567',
            'password_confirmation' => '1234567'
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_user_can_register_with_valid_data()
    {
        $response = $this->post('/register',[
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $this->assertDatabaseHas('users',[
            'email'=>'test@test.com'
        ]);
    }
}
