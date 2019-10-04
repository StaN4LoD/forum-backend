<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class TagsController extends Controller
{
//  Метод вывода всех Тэгов.

    public function index()
    {
        return Tag::tagsNameArray();
    }

//  Метод вывода конкретного Тэга.

    public function show(Tag $tag)
    {
        return response()->json($tag,200);
    }

//  Метод отправки содержимого форм в БД.

    public function store()
    {
        request()->validate(['name' => 'required']);

        return response()->json(Tag::create(request(['name'])),201);
    }

//  Метод сохранения изменений Тэга.

    public function update(Tag $tag)
    {
        $tag->update(request()->all());

        return response()->json($tag,200);
    }

//  Метод удаления Тэга.

    public function destroy($id) {

        Tag::findOrFail($id)->delete();

        return response()->noContent(204);
    }

}
