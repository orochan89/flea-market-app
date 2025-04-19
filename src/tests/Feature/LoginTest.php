<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_email_is_required_for_login()
    {
        $response = $this->post('/login', [
            'email' => '', // 入力なし
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);

        $response = $this->get('/login');

        $response->assertSee('メールアドレスを入力してください');
    }

    public function test_password_is_required_for_login()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '', //入力なし
        ]);

        $response->assertSessionHasErrors(['password']);

        $response = $this->get('/login');

        $response->assertSee('パスワードを入力してください');
    }

    public function test_login_with_invalid_credentials_shows_error_message()
    {
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'invalidpassword',
        ]); //未登録のユーザー

        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);

        $response = $this->get('/login');

        $response->assertSee('ログイン情報が登録されていません');
    }

    public function test_login_with_valid_credentials_redirects_to_home()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);


        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]); // 正しいemail, passwordを使用

        $response->assertRedirect('/mypage/profile'); //初回ログインの為profile登録画面へ遷移(userに紐づくprofileが存在するかでチェック)

        $this->assertAuthenticatedAs($user);
    }
}
