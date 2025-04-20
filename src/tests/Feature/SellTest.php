<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;

class SellTest extends TestCase
{
    use RefreshDatabase;

    // testcase ID:15 商品出品画面にて必要な情報が保存できること（カテゴリ、商品の状態、商品名、商品の説明、販売価格）
    public function test_user_can_store_product_with_valid_data()
    {
        $user = User::factory()->create()->first();
        Profile::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'image' => 'profiles/test_image.png',
        ]);
        $this->actingAs($user);

        $category1 = Category::create(['category' => 'メンズ']);
        $category2 = Category::create(['category' => 'ファッション']);

        $item = [
            'name' => 'クラッチバッグ',
            'condition' => 0,
            'brand' => 'leather',
            'detail' => '黒いレザーのクラッチバッグです',
            'price' => 3000,
            'image' => UploadedFile::fake()->create('test_image.png', 100, 'image/png'),
            'categories' => [$category1->id, $category2->id],
        ];

        $response = $this->post(route('sell'), $item);

        $response->assertRedirect(route('mypage'));

        $item = Item::where('name', 'クラッチバッグ')->first();
        $this->assertNotNull($item);

        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => $category1->id,
        ]);
        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => $category2->id,
        ]);
    }
}
