<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class LogoutTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

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
