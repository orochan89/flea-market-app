<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    // testcase ID:1 名前が入力されていない場合、バリデーションメッセージが表示される
    public function test_name_is_required_for_register()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['name']);

        $response = $this->get('/register');

        $response->assertSee('お名前を入力してください');
    }

    // testcase ID:1 メールアドレスが入力されていない場合、バリデーションメッセージが表示される
    public function test_email_is_required_for_register()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);

        $response = $this->get('/register');

        $response->assertSee('メールアドレスを入力してください');
    }

    // testcase ID:1 パスワードが入力されていない場合、バリデーションメッセージが表示される
    public function test_password_is_required_for_register()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password']);

        $response = $this->get('/register');

        $response->assertSee('パスワードを入力してください');
    }

    // testcase ID:1 パスワードが7文字以下の場合、バリデーションメッセージが表示される
    public function test_password_must_be_at_least_8_characters_for_register()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'pass123',
            'password_confirmation' => 'pass123',
        ]);

        $response->assertSessionHasErrors(['password']);

        $response = $this->get('/register');

        $response->assertSee('パスワードは8文字以上で入力してください');
    }

    // testcase ID:1 パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される
    public function test_password_must_match_confirmation_for_register()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ]);

        $response->assertSessionHasErrors(['password']);

        $response = $this->get('/register');

        $response->assertSee('パスワードと一致しません');
    }

    // testcase ID:1 全ての項目が入力されている場合、会員情報が登録され、ログイン画面に遷移される
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
