<?php
use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Маршрут для поиска
Route::get('/search/{keyword}', 'SearchController@search');

//Маршруты для постов
//Route::get('/posts/{type?}', 'PostsController@index'); // роут такого типа "мешает" роуту /posts/{post}
Route::get('/posts/', 'PostsController@index'); // заменен, работает
Route::get('/categories/{category}/posts','PostsController@categoryShow');
Route::get('/posts/{post}', 'PostsController@show'); // роут для конкретного поста
//Маршруты для комментариев
Route::get('/posts/{id}/comments/', 'CommentsController@index');
//Маршруты для Категорий
Route::get('/categories', 'CategoriesController@index');
Route::get('/categories/{category}', 'CategoriesController@show');
//Маршруты для Тегов
Route::get('/tags', 'TagsController@index');
Route::get('/tags/{tag}', 'TagsController@show');

//Маршруты для Пользователя
Route::group([
    'middleware' => ['not.post', 'api', 'cors'],
    'prefix' => 'auth'
], function ($router) {

    Route::post('login', 'AuthController@login'); //Авторизация пользователя. Возвращает user и token
    //Route::post('logout', 'AuthController@logout'); //Выход
    Route::post('refresh', 'AuthController@refresh'); //Обновление токена
//    Route::get('account', 'AuthController@me'); // Получение авторизованного пользователя. Возвращает user
    Route::get('me', 'AuthController@me'); // Получение авторизованного пользователя. Возвращает user
    Route::post('register', 'AuthController@register'); //Регистрация пользователя. Возвращает user, token, статус 201

    Route::get('/{id}', 'UsersInfoController@show');
    Route::put('/{id}', 'UsersInfoController@update');
});

//todo разобраться с временем жизни токена
Route::group(['middleware' => ['not.post', 'jwt.verify','user_id']], function () {
    //роуты для постов
    Route::post('/posts', 'PostsController@store');
    Route::put('/posts/{post}', 'PostsController@update');
    //роуты для комментариев
    Route::post('/posts/{id}/comments', 'CommentsController@store');
    Route::put('/comments/{id}', 'CommentsController@update');
    //роуты для тегов
    Route::post('/tags', 'TagsController@store');
    Route::patch('/tags/{tag}', 'TagsController@update');
    //роуты для категорий
    Route::post('/categories', 'CategoriesController@store');
    Route::put('/categories/{category}', 'CategoriesController@update');
});

//Роуты удаления
Route::group(['middleware' => ['not.post', 'jwt.verify'/*,'admin_val'*/]], function () {
    Route::delete('/posts/{post}', 'PostsController@delete');
    Route::delete('/comments/{id}', 'CommentsController@destroy');
    Route::delete('/tags/{tag}', 'TagsController@destroy');
    Route::delete('/categories/{category}', 'CategoriesController@destroy');
});