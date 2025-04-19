<?php

namespace Tests\Feature;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;

class CommentTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_user_can_post_comment_and_comment_count_increases()
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

        // 3. コメント投稿前のコメント数を記録
        $initialCommentCount = $item->comments()->count();

        // 4. コメント送信処理
        $response = $this->post(route('comment', ['item' => $item->id]), [
            'comment' => '商品状態を詳しく教えてください',
        ]);

        // 5. コメントがDBに保存されているか確認
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => '商品状態を詳しく教えてください',
        ]);

        // 6. コメント数が1増えていることを確認
        $this->assertEquals($initialCommentCount + 1, $item->comments()->count());

        // 7. リダイレクト確認（コメント後の詳細ページなど）
        $response->assertRedirect();
    }

    public function test_guest_user_cannot_post_comment()
    {
        // コメント対象のアイテムを作成
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

        // 未ログインでコメントをPOST
        $response = $this->post(route('comment', ['item' => $item->id]), [
            'comment' => 'ゲストのコメント',
        ]);

        // リダイレクトされる（たいていログインページへ）
        $response->assertRedirect(route('login'));

        // DBにコメントが保存されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'comment' => 'ゲストのコメント',
            'item_id' => $item->id,
        ]);
    }

    public function test_comment_validation_error_for_empty_input()
    {
        // ユーザー作成してログイン
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
        ]);
        // 1. 空のコメントで投稿
        $response = $this->post(route('comment', ['item' => $item->id]), [
            'comment' => '',
        ]);

        // セッションにバリデーションエラーが含まれていることを確認
        $response->assertSessionHasErrors(['comment']);
    }

    public function test_comment_validation_error_for_long_input()
    {
        // ユーザー作成してログイン
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
        ]);

        // 2. 256文字以上のコメントで投稿
        $longComment = str_repeat('あ', 256);
        $response = $this->post(route('comment', ['item' => $item->id]), [
            'comment' => $longComment,
        ]);

        $response->assertSessionHasErrors(['comment']);
    }
}
