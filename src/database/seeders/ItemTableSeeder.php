<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('id', '!=', 1)->inRandomOrder()->first();

        $categoryMap = [
            '腕時計' => ['メンズ', 'アクセサリー'],
            'HDD' => ['家電'],
            '玉ねぎ3束' => ['キッチン'],
            '革靴' => ['メンズ', 'ファッション'],
            'ノートPC' => ['家電'],
            'マイク' => ['家電'],
            'ショルダーバッグ' => ['ファッション', 'レディース'],
            'タンブラー' => ['キッチン'],
            'コーヒーミル' => ['キッチン'],
            'メイクセット' => ['コスメ', 'レディース'],
        ];

        $items = [
            [
                'user_id' => $user->id,
                'name' => '腕時計',
                'condition' => 0,
                'detail' => 'スタイリッシュなデザインのメンズ腕時計',
                'brand' => 'Armani',
                'price' => 15000,
                'image' => 'items/Armani+Mens+Clock.jpg'
            ],
            [
                'user_id' => $user->id,
                'name' => 'HDD',
                'condition' => 1,
                'detail' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'image' => 'items/HDD+Hard+Disk.jpg'
            ],
            [
                'user_id' => $user->id,
                'name' => '玉ねぎ3束',
                'condition' => 2,
                'detail' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
                'image' => 'items/iLoveIMG+d.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => '革靴',
                'condition' => 3,
                'detail' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'image' => 'items/Leather+Shoes+Product+Photo.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => 'ノートPC',
                'condition' => 0,
                'detail' => '高性能なノートパソコン',
                'price' => 45000,
                'image' => 'items/Living+Room+Laptop.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => 'マイク',
                'condition' => 1,
                'brand' => 'Maxim',
                'detail' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'image' => 'items/Music+Mic+4632231.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => 'ショルダーバッグ',
                'condition' => 2,
                'detail' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'image' => 'items/Purse+fashion+pocket.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => 'タンブラー',
                'condition' => 3,
                'detail' => '使いやすいタンブラー',
                'price' => 500,
                'image' => 'items/Tumbler+souvenir.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => 'コーヒーミル',
                'condition' => 0,
                'detail' => '手動のコーヒーミル',
                'price' => 4000,
                'image' => 'items/Waitress+with+Coffee+Grinder.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => 'メイクセット',
                'condition' => 1,
                'detail' => '便利なメイクアップセット',
                'price' => 2500,
                'image' => 'items/外出メイクアップセット.jpg',
            ]
        ];

        foreach ($items as $itemData) {
            $itemData['user_id'] = $user->id;
            $item = Item::create($itemData);
            $categoryNames = $categoryMap[$itemData['name']] ?? [];
            $categories = Category::whereIn('category', $categoryNames)->get();
            $item->categories()->attach($categories->pluck('id'));
        }
    }
}
