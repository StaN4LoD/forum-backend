<?php

use Illuminate\Database\Seeder;

class CategoriesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 5; $i++) {
            echo "Test";
            DB::table('categories')->insert([
                'name' => str_random(10),
                'status' => 1,
                'avatar' => str_random(10).'@mail.ru',
                'description' => str_random(50)
            ]);
        }
    }
}
