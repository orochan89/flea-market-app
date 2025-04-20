<?php

namespace Tests\Feature;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Purchase;

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

        $otherUser = User::factory()->create();

        Item::create([
            'user_id' => $user->id,
            'name' => 'クラッチバッグ',
            'condition' => 0,
            'brand' => 'leather',
            'detail' => '黒いレザーのクラッチバッグです',
            'price' => 3000,
            'image' => 'clutch_bag.jpg',
            'is_sold' => false,
        ]);
        $purchasedItem = Item::create([
            'user_id' => $otherUser->id,
            'name' => 'ショルダーバッグ',
            'condition' => 2,
            'brand' => 'cape',
            'detail' => '大きめのショルダーバッグです',
            'price' => 3000,
            'image' => 'shoulder_bag.jpg',
            'is_sold' => true,
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $purchasedItem->id,
            'payment' => 0,
            'postcode' => '111-1111',
            'address' => 'テスト市1丁目1-1',
            'building' => 'テストビル101',
        ]);

        $sellResponse = $this->get('/mypage?page=sell');
        $sellResponse->assertStatus(200);
        $sellResponse->assertSee('クラッチバッグ');
        $sellResponse->assertSee('clutch_bag.jpg');

        $buyResponse = $this->get('/mypage?page=buy');
        $buyResponse->assertStatus(200);
        $buyResponse->assertSee('ショルダーバッグ');
        $buyResponse->assertSee('shoulder_bag.jpg');

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
