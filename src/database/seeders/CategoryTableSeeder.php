<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['category' => 'ファッション'],
            ['category' => '家電'],
            ['category' => 'インテリア'],
            ['category' => 'レディース'],
            ['category' => 'メンズ'],
            ['category' => 'コスメ'],
            ['category' => '本'],
            ['category' => 'ゲーム'],
            ['category' => 'スポーツ'],
            ['category' => 'キッチン'],
            ['category' => 'ハンドメイド'],
            ['category' => 'アクセサリー'],
            ['category' => 'おもちゃ'],
            ['category' => 'ベビー・キッズ'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'categories' => $category
            ]);
        }
    }
}
