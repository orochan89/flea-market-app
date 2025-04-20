<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    // testcase ID:2 メールアドレスが入力されていない場合、バリデーションメッセージが表示される
    public function test_email_is_required_for_login()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);

        $response = $this->get('/login');

        $response->assertSee('メールアドレスを入力してください');
    }

    // testcase ID:2 パスワードが入力されていない場合、バリデーションメッセージが表示される
    public function test_password_is_required_for_login()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password']);

        $response = $this->get('/login');

        $response->assertSee('パスワードを入力してください');
    }

    // testcase ID:2 入力情報が間違っている場合、バリデーションメッセージが表示される
    public function test_login_with_invalid_credentials_shows_error_message()
    {
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'invalidpassword',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);

        $response = $this->get('/login');

        $response->assertSee('ログイン情報が登録されていません');
    }

    // testcase ID:2 正しい情報が入力された場合、ログイン処理が実行される
    public function test_login_with_valid_credentials_redirects_to_home()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);


        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/mypage/profile'); //初回ログインの為profile登録画面へ遷移(userに紐づくprofileが存在するかでチェック)

        $this->assertAuthenticatedAs($user);
    }
}
