<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        User::factory(5)->create();
        $this->call(ItemTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
    }
}
