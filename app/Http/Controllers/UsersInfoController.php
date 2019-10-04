<?php

namespace App\Http\Controllers;

use App\UsersInfo;

class UsersInfoController extends Controller
{

    public function show($id)
    {
        // чтобы при запросе несуществующего пользователя возвращалась ошибка 404
        $user_info = UsersInfo::findOrFail($id);
        return response()->json($user_info, 200);
    }

    public function update($id)
    {
        // чтобы при запросе несуществующего пользователя возвращалась ошибка 404
        $user_info = UsersInfo::findOrFail($id);
        $user_info->update
        ([
            'avatar' => request('avatar'),
            'full_name' => request('full_name'),
            'phone' => request('avatar'),
            'about' => request('avatar'),
            'rating' => request('rating'),
            'rule' => request('rule')
        ]);

        return response()->json($user_info, 200);
    }
}
