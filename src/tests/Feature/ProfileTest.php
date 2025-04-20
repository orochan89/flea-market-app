<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;

class ProfileTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_profile_page_displays_correct_information()
    {
        $user = User::factory()->create(['name' => 'テストユーザー'])->first();
        Profile::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'image' => 'default.png',
        ]);
        $this->actingAs($user);

        $response = $this->get(route('changeProfile'));

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区');
        $response->assertSee('テストビル101');
        $response->assertSee('default.png');
    }

    public function test_profile_edit_form_displays_previous_values_as_defaults()
    {
        $user = User::factory()->create(['name' => 'テストユーザー'])->first();
        Profile::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'image' => 'profiles/test_image.png',
        ]);
        $this->actingAs($user);

        $response = $this->get(route('changeProfile'));

        $response->assertStatus(200);

        // ユーザー名 input の value
        $response->assertSee('value="テストユーザー"', false);

        // 郵便番号 input の value
        $response->assertSee('value="123-4567"', false);

        // 住所 input の value
        $response->assertSee('value="東京都渋谷区"', false);

        // 建物名 input の value
        $response->assertSee('value="テストビル101"', false);

        // 画像の src 属性に過去設定された画像が含まれていること
        $response->assertSee('src="' . asset('storage/profiles/test_image.png') . '"', false);
    }
}
