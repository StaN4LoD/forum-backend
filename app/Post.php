<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Tag;
use App\PostTags;

/**
 * App\Post
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $category_id
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property int $views
 * @property int $like
 * @property int $dislike
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereDislike($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereLike($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereViews($value)
 */
class Post extends Model
{
    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'description',
        'content',
        'tags'
    ];
    public $timestamps = true;
    protected $with=['username','comments'];
    protected $appends=['tags'];
    protected $hidden=['tags_array'];

    public static function create($request) {
        $post = new Post();
        $post->fill($request);
        $post->save();
        $post->saveTags();
        return $post;
    }

    public function getTagsAttribute() {
        return array_pluck($this->tags_array,'name','name');
    }

    public function getOldTagsAttribute() {
        return array_pluck($this->tags_array,'name','name');
    }

    public function update(array $attributes = [], array $options = [])
    {
        if (parent::update($attributes, $options)) {
            $this->saveTags();
            return true;
        }
        return false;
    }

    public function username() {
        return $this->hasOne("App\User",'id','user_id')->select(['id','name']);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments() {
        return $this->hasMany('App\Comment','post_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    protected function tags_array() {
        return $this->hasManyThrough('App\Tag','App\PostTags','post_id','id',
            'id','tag_id')->select(['name']);
    }

    public function saveTags() {
        if (is_array($this->tags)) {
            $newTags=array_diff($this->tags,$this->oldTags);
            $deleteTags=array_diff($this->oldTags,$this->tags);
            $this->addTags($newTags);
            $this->deleteTags($deleteTags);
        } else {
            PostTags::query()->where('post_id',$this->id)->delete();
        }
    }

    /**
     * @param $newTags array
     */
    private function addTags($newTags) {
        foreach ($newTags as $newTag) {
            if(!$tag = Tag::query()->where(['name'=>$newTag])->first()) {
                $tag = new Tag();
                $tag->name=$newTag;
                if ($tag->save()) {
                    $tag = null;
                }
            }
            if ($tag instanceof Tag) {
                $pt = new PostTags();
                $pt->tag_id=$tag->id;
                $pt->post_id=$this->id;
                $pt->save();

            }
        }
    }

    /**
     * @param $deleteTags array
     */
    private function deleteTags($deleteTags) {
        foreach ($deleteTags as $deleteTag) {
            PostTags::query()->where(['tag_id'=>Tag::where(['name'=>$deleteTag])->value('id'),
                'post_id'=>$this->id])->delete();
        }
        /*return PostTags::query()->where(['tag_id'=>Tag::where(['name'=>$deleteTag])->value('id'),
                'post_id'=>$this->id]);*/
    }
}

