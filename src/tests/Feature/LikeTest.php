<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    // testcase ID:8 いいねアイコンを押下することによって、いいねした商品として登録する
    public function test_user_can_like_product_and_like_count_increases()
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

        $response = $this->get(route('detail', $item->id));
        $response->assertStatus(200);

        $initialLikeCount = $item->likes()->count();

        $response = $this->post(route('like.toggle', ['item' => $item->id]));

        $response->assertRedirect();
        $this->assertEquals($initialLikeCount + 1, $item->likes()->count());

        $response = $this->get(route('detail', $item->id));
        $response->assertSee(($initialLikeCount + 1));
    }

    // testcase ID:8 追加済みのアイコンは色が変化する
    public function test_liked_item_shows_filled_star_icon()
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

        $this->post(route('like.toggle', ['item' => $item->id]));
        $response = $this->get(route('detail', $item->id));

        $response->assertStatus(200);

        $response->assertSee('<i class="fas fa-star" style="color: yellow;"></i>', false);
    }
    // testcase ID:8 再度いいねアイコンを押下することによって、いいねを解除することができる
    public function test_user_can_unlike_product_and_like_count_decreases()
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
        ]);

        $this->post(route('like.toggle', ['item' => $item->id]));

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->post(route('like.toggle', ['item' => $item->id]));

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('detail', ['item' => $item->id]));
        $response->assertSee('0');
    }
}
