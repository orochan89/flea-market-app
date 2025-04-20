<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    // testcase ID:13 必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）
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

    // testcase ID:14 変更項目が初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所）
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

        $response->assertSee('value="テストユーザー"', false);

        $response->assertSee('value="123-4567"', false);

        $response->assertSee('value="東京都渋谷区"', false);

        $response->assertSee('value="テストビル101"', false);

        $response->assertSee('src="' . asset('storage/profiles/test_image.png') . '"', false);
    }
}
