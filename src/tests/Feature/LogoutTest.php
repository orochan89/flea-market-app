<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    // testcase ID:3 ログアウトができる
    public function test_logout()
    {
        $user = User::factory()->create()->first();
        Profile::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'image' => 'default.png',
        ]);

        $this->assertInstanceOf(\Illuminate\Contracts\Auth\Authenticatable::class, $user);

        $response = $this->actingAs($user)->get('/');

        $response = $this->post('/logout');

        $response->assertRedirect('/');

        $this->assertGuest();
    }
}
