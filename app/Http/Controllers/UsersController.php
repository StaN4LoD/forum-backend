<?php

namespace App\Http\Controllers;

use App\User;
use App\Users;
use App\UsersInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
//  Метод вывода всех Пользователей.

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Users::all(), 200);
    }

//  Метод вывода конкретного Пользователя.

    /**
     * @param Users $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Users $user)
    {
        return response()->json($user, 200);
    }

//  Метод отправки содержимого форм в БД.

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        request()->validate([
            'username' => 'required',
            'user_email' => 'required',
            'password' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $data = Users::create(request(['username', 'user_email', 'password']));
            dd($data['id']);
            UsersInfo::create(['id'=>$data['id']]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return response()->json($data, 201);
    }

//  Метод сохранения изменений Пользователей.

    /**
     * @param Users $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Users $user)
    {
        if (СheckWhoUpdated::check($user['id'])) {
            $user->update(request()->all());
            return response()->json($user, 200);
        } else {
            return response()->json(['Error' => 'You don\'t have rule'], 403);
        }
    }

//  Метод удаления Пользователя.

    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        if (СheckWhoUpdated::check($user['id'])) {
            $user->delete();
            return response()->noContent(204);
        } else {
            return response()->json(['Error' => 'You don\'t have rule'], 403);
        }
    }
}
