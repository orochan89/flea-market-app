<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;
use App\Models\Like;

class LikeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_user_can_like_product_and_like_count_increases()
    {
        // 1. ユーザーにログインする
        $user = User::factory()->create()->first();
        $profile = Profile::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'image' => 'default.png',
        ]);
        $this->actingAs($user);

        // 2. 商品を作成（既存の商品を使用することも可能）
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

        // 3. 商品詳細ページを開く
        $response = $this->get(route('detail', $item->id));
        $response->assertStatus(200);

        // 現在のいいね数を取得
        $initialLikeCount = $item->likes()->count();

        // 4. いいねアイコンを押下する
        $response = $this->post(route('like.toggle', ['item' => $item->id]));

        // 5. いいね合計値が増加していることを確認
        $response->assertRedirect(); // いいね後にリダイレクトされる想定
        $this->assertEquals($initialLikeCount + 1, $item->likes()->count());

        // 6. いいね数が表示されていることを確認
        $response = $this->get(route('detail', $item->id));
        $response->assertSee(($initialLikeCount + 1));
    }

    public function test_liked_item_shows_filled_star_icon()
    {
        // 1. ユーザーにログインする
        $user = User::factory()->create()->first();
        $profile = Profile::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'image' => 'default.png',
        ]);
        $this->actingAs($user);

        // 2. 商品を作成（既存の商品を使用することも可能）
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

        $this->post(route('like.toggle', ['item' => $item->id]));
        $response = $this->get(route('detail', $item->id));

        $response->assertStatus(200);

        $response->assertSee('<i class="fas fa-star" style="color: yellow;"></i>', false);
    }

    public function test_user_can_unlike_product_and_like_count_decreases()
    {
        // 1. ユーザー作成＆ログイン
        $user = User::factory()->create()->first();
        Profile::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'image' => 'default.png',
        ]);
        $this->actingAs($user);

        // 2. 商品作成
        $otherUser = User::factory()->create();
        $item = Item::create([
            'user_id' => $otherUser->id,
            'name' => 'クラッチバッグ',
            'condition' => 0,
            'brand' => 'leather',
            'detail' => '黒いレザーのクラッチバッグです',
            'price' => 3000,
            'image' => 'clutch_bag.jpg',
        ]);

        // 3. 初回のいいね
        $this->post(route('like.toggle', ['item' => $item->id]));

        // 4. いいねされていることを確認
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 5. 2回目のいいね（解除）
        $this->post(route('like.toggle', ['item' => $item->id]));

        // 6. いいねが解除されていることを確認
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 7. 詳細ページでいいね数が「0」であることを確認
        $response = $this->get(route('detail', ['item' => $item->id]));
        $response->assertSee('0');
    }
}
