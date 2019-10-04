<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Categories extends Model
{
    protected $fillable = ['name', 'status', 'avatar', 'description', 'created_at', 'updated_at'];

    public static function getAmountPosts($array = [])
    {
        foreach($array as $key => $item)
        {
            $array[$key]['posts'] = DB::table('posts')->where('category_id', $item->id)->count();
        }
        return $array;
    }
}

