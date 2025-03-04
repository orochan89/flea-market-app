<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
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
        $user = User::inRandomOrder()->first();

        $items = [
            [
                'user_id' => $user->id,
                'name' => '腕時計',
                'condition' => '良好',
                'detail' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'image' => 'items/Armani+Mens+Clock.jpg'
            ],
            [
                'user_id' => $user->id,
                'name' => 'HDD',
                'condition' => '目立った傷や汚れなし',
                'detail' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'image' => 'items/HDD+Hard+Disk.jpg'
            ],
            [
                'user_id' => $user->id,
                'name' => '玉ねぎ3束',
                'condition' => 'やや傷や汚れあり',
                'detail' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
                'image' => 'items/iLoveIMG+d.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => '革靴',
                'condition' => '状態が悪い',
                'detail' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'image' => 'items/Leather+Shoes+Product+Photo.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => 'ノートPC',
                'condition' => '良好',
                'detail' => '高性能なノートパソコン',
                'price' => 45000,
                'image' => 'items/Living+Room+Laptop.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => 'マイク',
                'condition' => '目立った傷や汚れなし',
                'detail' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'image' => 'items/Music+Mic+4632231.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => 'ショルダーバッグ',
                'condition' => 'やや傷や汚れあり',
                'detail' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'image' => 'items/Purse+fashion+pocket.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => 'タンブラー',
                'condition' => '状態が悪い',
                'detail' => '使いやすいタンブラー',
                'price' => 500,
                'image' => 'items/Tumbler+souvenir.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => 'コーヒーミル',
                'condition' => '良好',
                'detail' => '手動のコーヒーミル',
                'price' => 4000,
                'image' => 'items/Waitress+with+Coffee+Grinder.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => 'メイクセット',
                'condition' => '目立った傷や汚れなし',
                'detail' => '便利なメイクアップセット',
                'price' => 2500,
                'image' => 'items/外出メイクアップセット.jpg',
            ]
        ];

        DB::table('items')->insert($items);
    }
}
