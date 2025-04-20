<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    // testcase ID:10 「購入する」ボタンを押下すると購入が完了する
    public function test_user_can_purchase_item()
    {
        $user = User::factory()->create()->first();
        Profile::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'image' => 'default.png',
        ]);
        $this->actingAs($user);

        $otherUser = User::factory()->create();
        $item = Item::create([
            'user_id' => $otherUser->id,
            'name' => 'クラッチバッグ',
            'condition' => 0,
            'brand' => 'leather',
            'detail' => '黒いレザーのクラッチバッグです',
            'price' => 3000,
            'image' => 'clutch_bag.jpg',
            'is_sold' => false,
        ]);

        $payment = 0;
        $purchaseData = [
            'payment' => $payment,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
        ];
        $response = $this->post(route('purchase', ['item' => $item->id]), $purchaseData);

        $response->assertRedirect(route('mypage', ['page' => 'buy']));

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment' => $payment,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
        ]);
    }

    // testcase ID:10 購入した商品は商品一覧画面にて「SOLD」と表示される
    public function test_purchased_item_is_marked_as_sold_on_item_list()
    {
        $user = User::factory()->create()->first();
        Profile::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'image' => 'default.png',
        ]);
        $this->actingAs($user);

        $otherUser = User::factory()->create();
        $item = Item::create([
            'user_id' => $otherUser->id,
            'name' => 'クラッチバッグ',
            'condition' => 0,
            'brand' => 'leather',
            'detail' => '黒いレザーのクラッチバッグです',
            'price' => 3000,
            'image' => 'clutch_bag.jpg',
            'is_sold' => false,
        ]);

        $purchaseData = [
            'payment' => 0,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
        ];
        $this->post(route('purchase', ['item' => $item->id]), $purchaseData);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('sold');
    }

    // testcase ID:10 「プロフィール購入した商品一覧」に追加されている
    public function test_purchased_item_is_listed_in_user_profile()
    {
        // 1. ログインユーザーとプロフィール作成
        $user = User::factory()->create()->first();
        Profile::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'image' => 'default.png',
        ]);
        $this->actingAs($user);

        $otherUser = User::factory()->create();
        $item = Item::create([
            'user_id' => $otherUser->id,
            'name' => 'クラッチバッグ',
            'condition' => 0,
            'brand' => 'leather',
            'detail' => '黒いレザーのクラッチバッグです',
            'price' => 3000,
            'image' => 'clutch_bag.jpg',
            'is_sold' => false,
        ]);

        $purchaseData = [
            'payment' => 0,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
        ];
        $this->post(route('purchase', ['item' => $item->id]), $purchaseData);

        $response = $this->get(route('mypage', ['page' => 'buy']));

        $response->assertSee('クラッチバッグ');
        $response->assertSee('clutch_bag.jpg');
    }

    // testcase ID:12 送付先住所変更画面にて登録した住所が商品購入画面に反映されている
    public function test_address_reflects_on_purchase_page()
    {
        $user = User::factory()->create()->first();
        Profile::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'image' => 'default.png',
        ]);
        $this->actingAs($user);

        $otherUser = User::factory()->create();
        $item = Item::create([
            'user_id' => $otherUser->id,
            'name' => 'クラッチバッグ',
            'condition' => 0,
            'brand' => 'leather',
            'detail' => '黒いレザーのクラッチバッグです',
            'price' => 3000,
            'image' => 'clutch_bag.jpg',
            'is_sold' => false,
        ]);

        $response = $this->post(route('mailingAddress', ['item' => $item->id]), [
            'postcode' => '765-4321',
            'address' => '栃木県宇都宮市',
            'building' => 'テスト001',
        ]);

        $response->assertStatus(200);
        $response->assertSee('765-4321');
        $response->assertSee('栃木県宇都宮市');
        $response->assertSee('テスト001');
    }

    // testcase ID:12 購入した商品に送付先住所が紐づいて登録される
    public function test_purchase_saves_shipping_address()
    {
        $user = User::factory()->create()->first();
        Profile::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'image' => 'default.png',
        ]);
        $this->actingAs($user);

        $otherUser = User::factory()->create();
        $item = Item::create([
            'user_id' => $otherUser->id,
            'name' => 'クラッチバッグ',
            'condition' => 0,
            'brand' => 'leather',
            'detail' => '黒いレザーのクラッチバッグです',
            'price' => 3000,
            'image' => 'clutch_bag.jpg',
            'is_sold' => false,
        ]);

        $addressData = [
            'postcode' => '765-4321',
            'address' => '栃木県宇都宮市',
            'building' => 'テスト001',
        ];

        $response = $this->post(route('purchase', ['item' => $item->id]), array_merge($addressData, [
            'payment' => 1,
        ]));

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'postcode' => '765-4321',
            'address' => '栃木県宇都宮市',
            'building' => 'テスト001',
            'payment' => 1,
        ]);

        $response->assertRedirect(route('mypage', ['page' => 'buy']));
    }
}
