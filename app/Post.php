<?php

namespace App;

use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
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
    protected $with=['username'];
//    protected $appends=['tags'];
//    protected $hidden=['tags'];

    public static function create($request) {
        $post = new Post();
        $post->fill($request);
        //todo Разобраться с тегами
        $post['tags'] = $post->saveTags($request['tags']);
        $post->save();
        return $post;
    }

    public function updater($request)
    {
        $request['tags'] = $this->saveTags($request['tags']);
        return $request;
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
     * @param $meTags
     * @return string
     */
    public function saveTags($meTags) {
        $allTags = Tag::all();
        $baseTag = [];
        foreach ($allTags->toArray() as $key => $item )
        {
            $baseTag[$item['id']] = $item['name'];
        }
        $newTag = array_diff($meTags,$baseTag);
        $this->addTags($newTag);
        return implode(',',$meTags);
    }

    /**
     * @param $newTags array
     */
    private function addTags($newTags)
    {
        foreach ($newTags as $item) {
            Tag::create(['name'=> $item]);
        }
    }

    /**
     * @return Post[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function showAllPosts()
    {
        $posts = DB::select('
            select p.id,p.category_id,p.title,p.description,p.content,p.created_at,p.user_id,p.views,p.tags,
           (select name from users where id= p.user_id) as username,
           (select count(*) from comments where post_id = p.id) as comments
            from posts as p ORDER BY created_at DESC');
        $all_posts = collect($posts)->paginate(15);
        return response()->json($all_posts, 200);
    }

    public static function showCategoryPosts($id)
    {
        $posts = DB::select('
            select p.id,p.category_id,p.title,p.description,p.content,p.created_at,p.user_id,p.views,p.tags,
           (select name from users where id= p.user_id) as username,
           (select count(*) from comments where post_id = p.id) as comments
            from posts as p WHERE category_id=\''.$id.'\'  ORDER BY created_at DESC
        ');
        $category_posts = collect($posts)->paginate(15);
        return response()->json($category_posts, 200);
    }

    /**
     * @return Post[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function showBestPosts()
    {
        $posts = DB::select('select p.id,p.category_id,p.title,p.description,p.content,p.created_at,p.user_id,p.views,p.tags,
           (select name from users where id= p.user_id) as username,
           (select count(*) from comments where post_id = p.id) as comments
            from posts as p ORDER BY comments DESC');
        $best_posts = collect($posts)->paginate(15);
        return response()->json($best_posts,200);
    }

    protected static function addCommentCount($posts)
    {
        foreach($posts as $post)
        {
            $data = $post;
            $data['comments'] = DB::table('comments')->where('post_id', $post->id)->count();
        }
        return $posts;
    }
}

