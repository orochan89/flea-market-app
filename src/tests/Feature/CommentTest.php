<?php

namespace Tests\Feature;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    // testcase ID:9 ログイン済みのユーザーはコメントを送信できる
    public function test_user_can_post_comment_and_comment_count_increases()
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

        $initialCommentCount = $item->comments()->count();

        $response = $this->post(route('comment', ['item' => $item->id]), [
            'comment' => '商品状態を詳しく教えてください',
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => '商品状態を詳しく教えてください',
        ]);

        $this->assertEquals($initialCommentCount + 1, $item->comments()->count());

        $response->assertRedirect();
    }

    // testcase ID:9 ログイン前のユーザーはコメントを送信できない
    public function test_guest_user_cannot_post_comment()
    {
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

        $response = $this->post(route('comment', ['item' => $item->id]), [
            'comment' => 'ゲストのコメント',
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('comments', [
            'comment' => 'ゲストのコメント',
            'item_id' => $item->id,
        ]);
    }

    // testcase ID:9 コメントが入力されていない場合、バリデーションメッセージが表示される
    public function test_comment_validation_error_for_empty_input()
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

        $response = $this->post(route('comment', ['item' => $item->id]), [
            'comment' => '',
        ]);

        $response->assertSessionHasErrors(['comment']);
    }

    // testcase ID:9 コメントが255文字以上の場合、バリデーションメッセージが表示される
    public function test_comment_validation_error_for_long_input()
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

        $longComment = str_repeat('あ', 256);
        $response = $this->post(route('comment', ['item' => $item->id]), [
            'comment' => $longComment,
        ]);

        $response->assertSessionHasErrors(['comment']);
    }
}
