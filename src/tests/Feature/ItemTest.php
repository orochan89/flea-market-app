<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Contracts\Auth\Authenticatable;

class ItemTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

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
            'is_sold' => true, // 購入済み
        ]);

        $unsoldItem = Item::create([
            'user_id' => $user->id,
            'name' => '未購入商品',
            'condition' => 1,
            'brand' => 'ブランド2',
            'detail' => '詳細2',
            'price' => 2000,
            'image' => 'image2.jpg',
            'is_sold' => false, // 未購入
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

        // いいねしていない商品を作成
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

        // いいねした商品を作成
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

        // ユーザーがいいねを付ける
        $user->likes()->create([
            'item_id' => $likedItem->id
        ]);

        // ログインしてマイリストページ（tab=mylist）にアクセス
        $response = $this->actingAs($user)->get(route('home') . '?tab=mylist'); // URLにtab=mylistを付ける

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // いいねした商品が表示されていることを確認
        $response->assertSee($likedItem->name);
        $response->assertSee($likedItem->image);

        // いいねしていない商品が表示されていないことを確認
        $response->assertDontSee($unlikedItem->name);
        $response->assertDontSee($unlikedItem->image);
    }

    public function test_sold_item_displays_sold_label_on_mylist_tab()
    {
        // ユーザーを作成
        $user = User::factory()->create()->first();
        $profile = Profile::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'image' => 'default.png',
        ]);

        $otherUser = User::factory()->create();

        // 購入済みの商品を作成（is_sold = true）
        $likedSoldItem = Item::create([
            'user_id' => $otherUser->id,
            'name' => '購入済み商品',
            'condition' => 0,
            'brand' => 'ブランド名',
            'detail' => '商品の詳細',
            'price' => 3000,
            'image' => 'sold_item.jpg',
            'is_sold' => true, // 購入済み
        ]);

        $likedUnsoldItem = Item::create([
            'user_id' => $otherUser->id,
            'name' => '未購入商品',
            'condition' => 1,
            'brand' => 'ブランド2',
            'detail' => '詳細2',
            'price' => 2000,
            'image' => 'unsold_item.jpg',
            'is_sold' => false, // 未購入
        ]);

        $user->likes()->create([
            'item_id' => $likedSoldItem->id
        ]);
        $user->likes()->create([
            'item_id' => $likedUnsoldItem->id
        ]);

        // ログインしてマイリストページ（tab=mylist）にアクセス
        $response = $this->actingAs($user)->get(route('home') . '?tab=mylist');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 購入済み商品が「Sold」ラベルとともに表示されていることを確認
        $response->assertSee($likedSoldItem->name);
        $response->assertSee($likedSoldItem->image); // 商品名が表示されている
        $response->assertSee('SOLD'); // 「SOLD」ラベルが表示されている

        $html = $response->getContent();
        $unsoldBlock = collect(explode('<div class="item-preview">', $html))
            ->first(fn($block) => str_contains($block, $likedUnsoldItem->name));

        $this->assertNotFalse($unsoldBlock);
        $this->assertStringNotContainsString('SOLD', $unsoldBlock);
    }

    public function test_my_items_are_not_shown_in_mylist_tab()
    {
        // ログインユーザーと他ユーザー作成
        $user = User::factory()->create()->first();
        $profile = Profile::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'image' => 'default.png',
        ]);

        $otherUser = User::factory()->create();

        // 自分が出品した商品
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

        // 他人が出品した商品（いいね対象）
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
        // マイリストタブへアクセス
        $response = $this->actingAs($user)->get('/?tab=mylist');

        // ステータスコード確認
        $response->assertStatus(200);

        // 他人の商品は表示される
        $response->assertSee($likedOtherItem->name);
        $response->assertSee($likedOtherItem->image);

        // 自分の商品は表示されない
        $response->assertDontSee($likedMyItem->name);
        $response->assertDontSee($likedMyItem->image);
    }

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
        // ゲストユーザー（未認証）でマイリストページにアクセス
        $response = $this->get('/?tab=mylist');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // ページに「いいねした商品」や「マイリストの商品」が含まれていないことを確認
        $response->assertDontSee($otherItem->name);
        $response->assertDontSee($otherItem->image);
    }

    public function test_items_can_be_searched_by_partial_name()
    {
        $otherUser = User::factory()->create();
        // 商品を作成
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

        // 検索ワードを入力してリクエストを送信（今回は "りんご"）
        $response = $this->get('/?search=バッグ');

        $response->assertStatus(200);

        // 部分一致する商品が表示されることを確認
        $response->assertSee('クラッチバッグ');
        $response->assertSee('ショルダーバッグ');

        // 一致しない商品が表示されないことを確認
        $response->assertDontSee('カバン');
    }

    public function test_search_keyword_is_retained_on_mylist_tab()
    {
        // ユーザー作成＋ログイン
        $user = User::factory()->create()->first();
        $profile = Profile::create([
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

        // 検索キーワード付きでマイリストタブを開く
        $response = $this->get('/?tab=mylist&search=バッグ');

        $response->assertStatus(200);

        // 検索キーワードが検索フォームに保持されているか確認
        $response->assertSee('value="バッグ"', false);

        $response->assertSee('クラッチバッグ');
        $response->assertSee('ショルダーバッグ');

        $response->assertDontSee('カバン');
    }

    public function test_product_detail_page_displays_all_required_information()
    {
        $user = User::factory()->create()->first();
        $profile = Profile::create([
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
        // LikeとCommentのダミーデータ作成
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

        // 基本情報
        $response->assertSee('クラッチバッグ');
        $response->assertSee('良好');
        $response->assertSee('leather');
        $response->assertSee('黒いレザーのクラッチバッグです');
        $response->assertSee('3,000');
        $response->assertSee('clutch_bag.jpg');

        $response->assertSee('メンズ');

        // いいね数、コメント数
        $response->assertSeeText((string)$likeCount);
        $response->assertSeeText((string)$commentCount);

        // コメント詳細
        $response->assertSee($commenter->name);
        $response->assertSee('商品状態を詳しく教えてください');
    }

    public function test_product_detail_page_displays_all_required_information_with_multiple_categories()
    {
        $user = User::factory()->create()->first();
        $profile = Profile::create([
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
        $category2 = Category::create(['category' => 'ファッション']);

        $otherItem->categories()->attach([
            $category1->id,
            $category2->id
        ]);
        // LikeとCommentのダミーデータ作成
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

        // 基本情報
        $response->assertSee('クラッチバッグ');
        $response->assertSee('良好');
        $response->assertSee('leather');
        $response->assertSee('黒いレザーのクラッチバッグです');
        $response->assertSee('3,000');
        $response->assertSee('clutch_bag.jpg');

        $response->assertSee('メンズ');
        $response->assertSee('ファッション');

        // いいね数、コメント数
        $response->assertSeeText((string)$likeCount);
        $response->assertSeeText((string)$commentCount);

        // コメント詳細
        $response->assertSee($commenter->name);
        $response->assertSee('商品状態を詳しく教えてください');
    }
}
