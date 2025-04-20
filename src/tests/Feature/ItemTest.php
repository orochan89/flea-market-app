<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    // testcase ID:4 全商品を取得できる
    public function test_all_items_are_displayed_on_recommend_tab()
    {
        $user = User::factory()->create();

        Item::create([
            'user_id' => $user->id,
            'name' => '商品1',
            'condition' => 0,
            'brand' => 'ブランド1',
            'detail' => '詳細1',
            'price' => 1000,
            'image' => 'image1.jpg',
            'is_sold' => false,
        ]);
        Item::create([
            'user_id' => $user->id,
            'name' => '商品2',
            'condition' => 1,
            'brand' => 'ブランド2',
            'detail' => '詳細2',
            'price' => 2000,
            'image' => 'image2.jpg',
            'is_sold' => false,
        ]);
        Item::create([
            'user_id' => $user->id,
            'name' => '商品3',
            'condition' => 2,
            'brand' => 'ブランド3',
            'detail' => '詳細3',
            'price' => 3000,
            'image' => 'image3.jpg',
            'is_sold' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertSee('商品1');
        $response->assertSee('商品2');
        $response->assertSee('商品3');
    }

    // testcase ID:4 購入済み商品は「SOLD」と表示される
    public function test_sold_items_are_displayed_as_sold_on_recommend_tab()
    {
        $user = User::factory()->create();

        $soldItem = Item::create([
            'user_id' => $user->id,
            'name' => '購入済み商品',
            'condition' => 0,
            'brand' => 'ブランド名',
            'detail' => '詳細説明',
            'price' => 3000,
            'image' => 'test.jpg',
            'is_sold' => true,
        ]);

        $unsoldItem = Item::create([
            'user_id' => $user->id,
            'name' => '未購入商品',
            'condition' => 1,
            'brand' => 'ブランド2',
            'detail' => '詳細2',
            'price' => 2000,
            'image' => 'image2.jpg',
            'is_sold' => false,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertSee($soldItem->name);
        $response->assertSee('SOLD');

        $html = $response->getContent();

        $unsoldBlock = collect(explode('<div class="item-preview">', $html))
            ->first(fn($block) => str_contains($block, $unsoldItem->name));

        $this->assertNotFalse($unsoldBlock);
        $this->assertStringNotContainsString('SOLD', $unsoldBlock);
    }

    // testcase ID:4 自分が出品した商品は表示されない
    public function test_user_cannot_see_own_items_on_recommend_tab()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create()->first();
        $profile = Profile::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'image' => 'default.png',
        ]);

        $otherUser = User::factory()->create();

        $myItem = Item::create([
            'user_id' => $user->id,
            'name' => '自分の商品',
            'condition' => 1,
            'brand' => 'MyBrand',
            'detail' => '詳細',
            'price' => 1000,
            'image' => 'myitem.jpg',
            'is_sold' => false,
        ]);

        $otherItem = Item::create([
            'user_id' => $otherUser->id,
            'name' => '他人の商品',
            'condition' => 2,
            'brand' => 'OtherBrand',
            'detail' => '詳細',
            'price' => 2000,
            'image' => 'otheritem.jpg',
            'is_sold' => false,
        ]);

        $this->assertInstanceOf(\Illuminate\Contracts\Auth\Authenticatable::class, $user);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);

        $response->assertDontSee($myItem->name);
        $response->assertDontSee($myItem->image);

        $response->assertSee($otherItem->name);
        $response->assertSee($otherItem->image);
    }

    // testcase ID:5 いいねした商品だけが表示される
    public function test_only_liked_items_are_displayed_on_mylist_tab()
    {
        $user = User::factory()->create()->first();
        $profile = Profile::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'image' => 'default.png',
        ]);
        $otherUser = User::factory()->create();

        $unlikedItem = Item::create([
            'user_id' => $otherUser->id,
            'name' => '未いいねの商品',
            'condition' => 0,
            'brand' => 'ブランド名',
            'detail' => '商品の詳細',
            'price' => 1000,
            'image' => 'image.jpg',
            'is_sold' => false,
        ]);

        $likedItem = Item::create([
            'user_id' => $otherUser->id,
            'name' => 'いいねした商品',
            'condition' => 1,
            'brand' => 'ブランド名',
            'detail' => '商品の詳細',
            'price' => 2000,
            'image' => 'image2.jpg',
            'is_sold' => false,
        ]);

        $user->likes()->create([
            'item_id' => $likedItem->id
        ]);

        $response = $this->actingAs($user)->get(route('home') . '?page=mylist');

        $response->assertStatus(200);

        $response->assertSee($likedItem->name);
        $response->assertSee($likedItem->image);

        $response->assertDontSee($unlikedItem->name);
        $response->assertDontSee($unlikedItem->image);
    }

    // testcase ID:5 購入済み商品は「SOLD」と表示される
    public function test_sold_item_displays_sold_label_on_mylist_tab()
    {
        $user = User::factory()->create()->first();
        $profile = Profile::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'image' => 'default.png',
        ]);

        $otherUser = User::factory()->create();

        $likedSoldItem = Item::create([
            'user_id' => $otherUser->id,
            'name' => '購入済み商品',
            'condition' => 0,
            'brand' => 'ブランド名',
            'detail' => '商品の詳細',
            'price' => 3000,
            'image' => 'sold_item.jpg',
            'is_sold' => true,
        ]);

        $likedUnsoldItem = Item::create([
            'user_id' => $otherUser->id,
            'name' => '未購入商品',
            'condition' => 1,
            'brand' => 'ブランド2',
            'detail' => '詳細2',
            'price' => 2000,
            'image' => 'unsold_item.jpg',
            'is_sold' => false,
        ]);

        $user->likes()->create([
            'item_id' => $likedSoldItem->id
        ]);
        $user->likes()->create([
            'item_id' => $likedUnsoldItem->id
        ]);

        $response = $this->actingAs($user)->get(route('home') . '?page=mylist');

        $response->assertStatus(200);

        $response->assertSee($likedSoldItem->name);
        $response->assertSee($likedSoldItem->image);
        $response->assertSee('SOLD');

        $html = $response->getContent();
        $unsoldBlock = collect(explode('<div class="item-preview">', $html))
            ->first(fn($block) => str_contains($block, $likedUnsoldItem->name));

        $this->assertNotFalse($unsoldBlock);
        $this->assertStringNotContainsString('SOLD', $unsoldBlock);
    }

    // testcase ID:5 自分が出品した商品は表示されない
    public function test_my_items_are_not_shown_in_mylist_tab()
    {
        $user = User::factory()->create()->first();
        Profile::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'image' => 'default.png',
        ]);

        $otherUser = User::factory()->create();

        $likedMyItem = Item::create([
            'user_id' => $user->id,
            'name' => '自分の商品',
            'condition' => 1,
            'brand' => '自分ブランド',
            'detail' => 'これは自分の商品です',
            'price' => 5000,
            'image' => 'my_item.jpg',
            'is_sold' => false,
        ]);

        $likedOtherItem = Item::create([
            'user_id' => $otherUser->id,
            'name' => '他人の商品',
            'condition' => 0,
            'brand' => '他人ブランド',
            'detail' => 'これは他人の商品です',
            'price' => 3000,
            'image' => 'liked_item.jpg',
            'is_sold' => false,
        ]);

        $user->likes()->create([
            'item_id' => $likedMyItem->id
        ]);
        $user->likes()->create([
            'item_id' => $likedOtherItem->id
        ]);

        $response = $this->actingAs($user)->get('/?page=mylist');

        $response->assertStatus(200);

        $response->assertSee($likedOtherItem->name);
        $response->assertSee($likedOtherItem->image);

        $response->assertDontSee($likedMyItem->name);
        $response->assertDontSee($likedMyItem->image);
    }

    // testcase ID:5 未認証の場合は何も表示されない
    public function test_mylist_page_shows_nothing_for_guest()
    {
        $otherUser = User::factory()->create();

        $otherItem = Item::create([
            'user_id' => $otherUser->id,
            'name' => '他人の商品',
            'condition' => 0,
            'brand' => '他人ブランド',
            'detail' => 'これは他人の商品です',
            'price' => 3000,
            'image' => 'unsold_item.jpg',
            'is_sold' => false,
        ]);

        $response = $this->get('/?page=mylist');

        $response->assertStatus(200);

        $response->assertDontSee($otherItem->name);
        $response->assertDontSee($otherItem->image);
    }

    // testcase ID:6 「商品名」で部分一致検索ができる
    public function test_items_can_be_searched_by_partial_name()
    {
        $otherUser = User::factory()->create();

        $otherItem1 = Item::create([
            'user_id' => $otherUser->id,
            'name' => 'クラッチバッグ',
            'condition' => 0,
            'brand' => '他人ブランド',
            'detail' => 'これは他人の商品です',
            'price' => 1000,
            'image' => 'item1_image.jpg',
            'is_sold' => false,
        ]);
        $otherItem2 = Item::create([
            'user_id' => $otherUser->id,
            'name' => 'ショルダーバッグ',
            'condition' => 0,
            'brand' => '他人ブランド',
            'detail' => 'これは他人の商品です',
            'price' => 2000,
            'image' => 'item2_image.jpg',
            'is_sold' => false,
        ]);
        $otherItem3 = Item::create([
            'user_id' => $otherUser->id,
            'name' => 'カバン',
            'condition' => 0,
            'brand' => '他人ブランド',
            'detail' => 'これは他人の商品です',
            'price' => 3000,
            'image' => 'item3_image.jpg',
            'is_sold' => false,
        ]);

        $response = $this->get('/?search=バッグ');

        $response->assertStatus(200);

        $response->assertSee('クラッチバッグ');
        $response->assertSee('ショルダーバッグ');

        $response->assertDontSee('カバン');
    }

    // testcase ID:6 検索状態がマイリストでも保持されている
    public function test_search_keyword_is_retained_on_mylist_tab()
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

        $otherItem1 = Item::create([
            'user_id' => $otherUser->id,
            'name' => 'クラッチバッグ',
            'condition' => 0,
            'brand' => '他人ブランド',
            'detail' => 'これは他人の商品です',
            'price' => 1000,
            'image' => 'item1_image.jpg',
            'is_sold' => false,
        ]);
        $otherItem2 = Item::create([
            'user_id' => $otherUser->id,
            'name' => 'ショルダーバッグ',
            'condition' => 0,
            'brand' => '他人ブランド',
            'detail' => 'これは他人の商品です',
            'price' => 2000,
            'image' => 'item2_image.jpg',
            'is_sold' => false,
        ]);
        $otherItem3 = Item::create([
            'user_id' => $otherUser->id,
            'name' => 'カバン',
            'condition' => 0,
            'brand' => '他人ブランド',
            'detail' => 'これは他人の商品です',
            'price' => 3000,
            'image' => 'item3_image.jpg',
            'is_sold' => false,
        ]);

        $user->likes()->create([
            'item_id' => $otherItem1->id
        ]);
        $user->likes()->create([
            'item_id' => $otherItem2->id
        ]);
        $user->likes()->create([
            'item_id' => $otherItem3->id
        ]);

        $response = $this->get('/?page=mylist&search=バッグ');

        $response->assertStatus(200);

        $response->assertSee('value="バッグ"', false);

        $response->assertSee('クラッチバッグ');
        $response->assertSee('ショルダーバッグ');

        $response->assertDontSee('カバン');
    }

    //// testcase ID:7 必要な情報が表示される（商品画像、商品名、ブランド名、価格、いいね数、コメント数、商品説明、商品情報（カテゴリ、商品の状態）、コメント数、コメントしたユーザー情報、コメント内容）
    public function test_product_detail_page_displays_all_required_information()
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

        $commenter = User::factory()->create();
        $commenterProfile = Profile::create([
            'user_id' => $commenter->id,
            'postcode' => '111-1111',
            'address' => '栃木県宇都宮市',
            'building' => 'テストビル202',
            'image' => 'default.png',
        ]);


        $otherItem = Item::create([
            'user_id' => $otherUser->id,
            'name' => 'クラッチバッグ',
            'condition' => 0,
            'brand' => 'leather',
            'detail' => '黒いレザーのクラッチバッグです',
            'price' => 3000,
            'image' => 'clutch_bag.jpg',
            'is_sold' => false,
        ]);

        $category1 = Category::create(['category' => 'メンズ']);

        $otherItem->categories()->attach([
            $category1->id,
        ]);

        $otherItem->likes()->create(['user_id' => $user->id]);
        $otherItem->likes()->create(['user_id' => $commenter->id]);

        $otherItem->comments()->create([
            'user_id' => $commenter->id,
            'comment' => '商品状態を詳しく教えてください'
        ]);


        $likeCount = $otherItem->likes()->count();
        $commentCount = $otherItem->comments()->count();

        $response = $this->get(route('detail', $otherItem->id));

        $response->assertStatus(200);

        $response->assertSee('クラッチバッグ');
        $response->assertSee('良好');
        $response->assertSee('leather');
        $response->assertSee('黒いレザーのクラッチバッグです');
        $response->assertSee('3,000');
        $response->assertSee('clutch_bag.jpg');

        $response->assertSee('メンズ');

        $response->assertSeeText((string)$likeCount);
        $response->assertSeeText((string)$commentCount);

        $response->assertSee($commenter->name);
        $response->assertSee('商品状態を詳しく教えてください');
    }

    // testcase ID:7 複数選択されたカテゴリが表示されているか
    public function test_product_detail_page_displays_all_required_information_with_multiple_categories()
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

        $commenter = User::factory()->create();
        Profile::create([
            'user_id' => $commenter->id,
            'postcode' => '111-1111',
            'address' => '栃木県宇都宮市',
            'building' => 'テストビル202',
            'image' => 'default.png',
        ]);


        $otherItem = Item::create([
            'user_id' => $otherUser->id,
            'name' => 'クラッチバッグ',
            'condition' => 0,
            'brand' => 'leather',
            'detail' => '黒いレザーのクラッチバッグです',
            'price' => 3000,
            'image' => 'clutch_bag.jpg',
            'is_sold' => false,
        ]);

        $category1 = Category::create(['category' => 'メンズ']);
        $category2 = Category::create(['category' => 'ファッション']);

        $otherItem->categories()->attach([
            $category1->id,
            $category2->id
        ]);

        $otherItem->likes()->create(['user_id' => $user->id]);
        $otherItem->likes()->create(['user_id' => $commenter->id]);

        $otherItem->comments()->create([
            'user_id' => $commenter->id,
            'comment' => '商品状態を詳しく教えてください'
        ]);


        $likeCount = $otherItem->likes()->count();
        $commentCount = $otherItem->comments()->count();

        $response = $this->get(route('detail', $otherItem->id));

        $response->assertStatus(200);

        $response->assertSee('クラッチバッグ');
        $response->assertSee('良好');
        $response->assertSee('leather');
        $response->assertSee('黒いレザーのクラッチバッグです');
        $response->assertSee('3,000');
        $response->assertSee('clutch_bag.jpg');

        $response->assertSee('メンズ');
        $response->assertSee('ファッション');

        $response->assertSeeText((string)$likeCount);
        $response->assertSeeText((string)$commentCount);

        $response->assertSee($commenter->name);
        $response->assertSee('商品状態を詳しく教えてください');
    }
}
