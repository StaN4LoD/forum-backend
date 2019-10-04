<?php

namespace App\Http\Controllers;

use App\Comment;
use App\User;
use Illuminate\Http\Request;
use App\Utilities\СheckWhoUpdated;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($post_id)
    {
        $data = Comment::all(['id','post_id','user_id','content','created_at'])->where('post_id', $post_id)->paginate(10);
        return response()->json($data,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$post_id)
    {
        request()->validate
        ([
            'user_id' => 'required',
            'content' => 'required',
        ]);
        $data = value_validation($request->all());
        $data['post_id'] = $post_id;
        $data = Comment::create($data); // Запись комментария в базу
        $data['username'] = User::where('id',$data['user_id'])->first()['name'];//Добавление имени пользователя
        return response()->json($data, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $comment)
    {
        $comment = Comment::find($comment);
        if (СheckWhoUpdated::check($comment['user_id'])) {
            $data = value_validation($request->all());
            $comment->update($data);
            return response()->json($comment, 200);
        } else {
            return response()->json(['Error' => 'You don\'t have rule'], 403);
        }

    }


    /**
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($comment)
    {
        $comment = Comment::find($comment);
        if (СheckWhoUpdated::check($comment['user_id'])) {
            $comment->delete();
            return response()->noContent(204);
        } else {
            return response()->json(['Error' => 'You don\'t have rule'], 403);
        }
    }
}
