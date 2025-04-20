<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    // testcase ID:3 ログアウトができる
    public function test_logout()
    {
        $user = User::factory()->create()->first();

        $this->assertInstanceOf(\Illuminate\Contracts\Auth\Authenticatable::class, $user);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(302);

        $response = $this->post('/logout');

        $response->assertRedirect('/');

        $this->assertGuest();
    }
}
