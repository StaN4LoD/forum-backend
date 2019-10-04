<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Post;
use App\Utilities\СheckWhoUpdated;
use Illuminate\Support\Facades\Input;

class PostsController extends Controller
{
    public function index()
    {
        $data = Input::get('type');
        if ($data == 'best')
        {
            return Post::showBestPosts();
        }
        else
        {
            return Post::showAllPosts();
        }
    }

    /**
     * @return Post[]|\Illuminate\Database\Eloquent\Collection
     */
    public function categoryShow($id)
    {
        return Post::showCategoryPosts($id);
    }

    /**
     * @param Post $post
     * @return Post[]|\Illuminate\Database\Eloquent\Collection
     */
    //todo сломался счетчик - ПОЧИНЕН
    /**
     * @param $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($postId)
    {
        $post = Post::find($postId);
        $post->views++;
        $post->timestamps=false;
        $post->save();
        if (time() <= strtotime($post->created_at) + 3600) {
            $post['canEdit']=true;
        } else {
            $post['canEdit']=false;
        }
        return response()->json($post, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'category_id' => 'required',
            'title' => 'required',
            'content' => 'required'
        ]);
        $post = Post::create($request->all());
        return response()->json($post, 201);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return Post|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Post $post)
    {
        if (СheckWhoUpdated::check($post['user_id'])) {
            if (time() <= strtotime($post->created_at) + 3600) {
                $this->validate($request, [
                    'title' => 'required',
                    'content' => 'required'
                ]);
                $post->update($post->updater($request->all()));
                return response()->json($post, 200);
            } else {
                return response()->json(['Error' => 'Timeout'], 400);
            }
        } else {
            return response()->json(['Error' => 'You don\'t have rule'], 403);
        }
    }

    /**
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Post $post)
    {
        if (СheckWhoUpdated::check($post['user_id'])) {
            $post->delete();
            return response()->json(null, 204);
        } else {
            return response()->json(['Error' => 'You don\'t have rule'], 403);
        }

    }
}

