<?php

namespace App\Http\Controllers;

use App\Categories;
use App\Post;
use Illuminate\Http\Request;
use App\Utilities\СheckWhoUpdated;

class CategoriesController extends Controller
{
//  Метод вывода всех Категорий.

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data = Categories::all();
        $data = Categories::getAmountPosts($data);
        return response()->json($data, 200);
    }

//  Метод вывода конкретной Категории.

    /**
     * @param Categories $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Categories $category)
    {
        $data = $category;
        $data['posts'] = Post::All()->where('category_id', $category->id)->count();
        return response()->json($data, 200);
    }

//  Метод отправки содержимого форм в БД.

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'avatar' => 'required',
            'description' => 'required'
        ]);

        $data = value_validation(request(['name', 'description', 'avatar']));
        $data['status'] = true;//пока заглушка

        $category = Categories::create($data);
        return response()->json($category, 201);
    }

//  Метод сохранения изменений в Категории.

    /**
     * @param Categories $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Categories $category)
    {
        request()->validate([
            'name' => 'required',
            'avatar' => 'required',
            'description' => 'required'
        ]);

        if (СheckWhoUpdated::check($category['user_id'])) {
            $category->update(request()->all());
            return response()->json($category, 201);
        } else {
            return response()->json(['Error' => 'You don\' have rule'], 403);
        }
    }

//  Метод Удаления Категории.

    public function destroy(Categories $category)
    {
        if (СheckWhoUpdated::check($category['user_id'])) {
            $category->delete();
            return response()->noContent(204);
        } else {
            return response()->json(['Error' => 'You don\' have rule'], 403);
        }
    }
}

