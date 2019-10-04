<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    public $timestamps=false;

    public static function tagsNameArray()
    {
        $tags = Tag::all('name');
        $arr_tags =[];
        foreach ($tags as $tag_name)
        {
            array_push($arr_tags, $tag_name->name);
        }
        return $arr_tags;
    }
}
