<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_name_is_required_for_register()
    {
        $response = $this->post('/register', [
            'name' => '', // 入力なし
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['name']);

        $response = $this->get('/register');

        $response->assertSee('お名前を入力してください');
    }

    public function test_email_is_required_for_register()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => '', //入力なし
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);

        $response = $this->get('/register');

        $response->assertSee('メールアドレスを入力してください');
    }

    public function test_password_is_required_for_register()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '', //入力なし
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password']);

        $response = $this->get('/register');

        $response->assertSee('パスワードを入力してください');
    }

    public function test_password_must_be_at_least_8_characters_for_register()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'pass123', // 7文字
            'password_confirmation' => 'pass123',
        ]);

        $response->assertSessionHasErrors(['password']);

        $response = $this->get('/register');

        $response->assertSee('パスワードは8文字以上で入力してください');
    }

    public function test_password_must_match_confirmation_for_register()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different123', // パスワード不一致
        ]);

        $response->assertSessionHasErrors(['password']);

        $response = $this->get('/register');

        $response->assertSee('パスワードと一致しません');
    }

    public function test_successful_registration_creates_user_and_redirects_to_login_for_register()
    {
        $payload = [
            'name'                  => 'テストユーザー',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $payload);

        $this->assertDatabaseHas('users', [
            'email' => $payload['email'],
            'name'  => $payload['name'],
        ]);

        $response->assertRedirect('/login');
    }
}
