<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;

class PurchaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
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

        // アイテム作成
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

        // 3. 「購入する」ボタンを押す（購入処理のルートをPOSTすると仮定）
        $payment = 0;
        $purchaseData = [
            'payment' => $payment,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
        ];
        $response = $this->post(route('purchase', ['item' => $item->id]), $purchaseData);

        // 4. 正しくリダイレクト or 完了画面へ遷移
        $response->assertRedirect(route('mypage', ['tab' => 'buy']));

        // 5. 購入情報がDBに登録されたか確認（Purchaseモデルがある前提）
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment' => $payment,
            'postcode' => '123-4567',  // 送付先の郵便番号
            'address' => '東京都渋谷区', // 住所
            'building' => 'テストビル101', // 建物名
        ]);
    }

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

        // 他のユーザーの商品を作成
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

        // 購入処理を実行
        $purchaseData = [
            'payment' => 0,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
        ];
        $this->post(route('purchase', ['item' => $item->id]), $purchaseData);

        // 商品一覧ページを表示
        $response = $this->get(route('home')); // 商品一覧のルート名を適宜変更

        // sold 表示があるか確認
        $response->assertStatus(200);
        $response->assertSee('sold');
    }

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

        // 2. 他のユーザーの商品を作成
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

        // 3. 商品を購入
        $purchaseData = [
            'payment' => 0,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
        ];
        $this->post(route('purchase', ['item' => $item->id]), $purchaseData);

        // 4. プロフィール画面（購入履歴タブ）を表示
        $response = $this->get(route('mypage', ['tab' => 'buy']));

        // 5. 購入した商品名が表示されているか確認
        $response->assertSee('クラッチバッグ');
        $response->assertSee('clutch_bag.jpg');
    }

    public function test_payment_method_selection_reflects_on_checkout_page()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        // 商品データ作成
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

        // 支払い方法を送信（例: 0 = コンビニ払い, 1 = カード払い）
        $response = $this->post(route('purchase', ['item' => $item->id]), [
            'payment' => 1, // カード払いを選択
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
        ]);

        $response->assertStatus(302); // リダイレクト確認
        $response->assertRedirect(route('purchase', ['item' => $item->id]));

        $summaryResponse = $this->get(route('purchase', ['item' => $item->id]));

        $summaryResponse->assertStatus(200); // ページが正常に表示されることを確認
        $summaryResponse->assertSee('カード払い'); // 支払い方法が正しく反映されていることを確認
    }
}
