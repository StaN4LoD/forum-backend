<?php

use Illuminate\Database\Seeder;

class CommentariesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 50; $i++) {
            DB::table('comments')->insert([
                'user_id' => random_int(1,10),
                'post_id' => random_int(1,20),
                'content' => str_random(50)
            ]);
        }
    }
}
