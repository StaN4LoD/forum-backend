<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(CategoriesSeed::class,10);
        factory(App\User::class,'admin',1)->create();
        factory(App\User::class,5)->create();
        factory(App\UsersInfo::class,6)->create();
        factory(App\Categories::class,4)->create();
        factory(App\Post::class,10)->create();
        factory(App\Comment::class,25)->create();
    }
}
