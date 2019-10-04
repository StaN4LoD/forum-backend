<?php

namespace Tests\Feature;

use Faker\Factory;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DatabaseSeed;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class CommentsTest extends TestCase
{
    use DatabaseSeed;

    /**
     * Вспомогательная функция для логина
     * @param $login
     * @return array
     */
    public function Login($login)
    {

        $login = $this->json('POST','/users/login',[
            'email' => $login,
            'password' => 'secret'
        ])->original;
        return ['authorization'=> 'Bearer '.$login['access_token']];
    }

    /**
     * вспомогательная функция для создания комментария
     * @param $mail
     * @param $text
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function createComments($mail,$text)
    {
        return $this->withHeaders($this->login($mail))->json('POST','/posts/1/comments',
            ['content'=>$text]);
    }


    /**
     * тест гет запроса
     */
    public function testGetComments():void
    {
        $response = $this->json('GET', '/posts/1/comments');
        $response->assertOk();
        $response->assertJsonStructure(['data'=>[]]);
    }

    /**
     * тест гет запроса на содержание
     */
    public function testGetCommentsContent():void
    {
        $response = $this->json('GET', '/posts/1/comments');
        $res = json_decode($response->content(),true);
        foreach ($res['data'] as $item) {
            $this->assertIsNumeric($item['id']);
            $this->assertIsNumeric($item['post_id']);
            $this->assertIsNumeric($item['user_id']);
            $this->assertIsString($item['content']);
            $this->assertIsArray($item['username']);
            $this->assertIsString($item['username']['name']);
        }
    }


    /**
     * создание комментария без авторизации
     */
    public function testCreateCommentsFailAuth():void
    {
        $response = $this->json('POST','/posts/1/comments',['content'=>'test']);
        $response->assertStatus(403);
    }

    /**
     * создание комментария
     */
    public function testCreateComments():void
    {
        $faker = \Faker\Factory::create('ru_RU');
        $text = $faker->realText(100);
        $response = $this->createComments('admin@mail.ru',$text);
        $res = $response->original;
        $this->assertEquals($res['content'],$text);
        $response->assertStatus(201);
    }

    /**
     * пользователь не может корректировать чужой комментарий
     */
    public function testUpdateCommentsFail():void
    {
        $testComments = $this->createComments('admin@mail.ru','testText')->original;
        $id = $testComments['id'];
        $email = User::find(2)['email'];
        $response = $this->withHeaders($this->login($email))
            ->json('PUT','comments/'.$id,['content'=>'testText']);
        $response->assertStatus(403);
    }

    /**
     * пользователь может корректировать свой комментарий
     */
    public function testUpdateCommentsUser():void
    {
        $email = User::find(2)['email'];
        $response = $this->createComments($email,'testText')->original;
        $id = $response['id'];
        $response = $this->withHeaders($this->login($email))
            ->json('PUT','comments/'.$id,['content'=>'testText']);
        $response->assertStatus(200);
    }

    /**
     * админ может корректировать любые комментарии
     */
    public function testUpdateCommentsAdmin():void
    {
        $email = User::find(2)['email'];
        $response = $this->createComments($email,'testText')->original;
        $id = $response['id'];
        $response = $this->withHeaders($this->login('admin@mail.ru'))
            ->json('PUT','comments/'.$id,['content'=>'testText']);
        $response->assertStatus(200);
    }

    /**
     * пользователь не может удалить чужой комментарий
     */
    public function testDeleteCommentsFail():void
    {
        $testComments = $this->createComments('admin@mail.ru','testText')->original;
        $id = $testComments['id'];
        $email = User::find(2)['email'];
        $response = $this->withHeaders($this->login($email))
                        ->json('DELETE','comments/'.$id);
        $response->assertStatus(403);
    }

    /**
     * Пользователь может удалить свой комментарий
     */
    public function testDeleteCommentsUser():void
    {
        $email = User::find(2)['email'];
        $response = $this->createComments($email,'testText')->original;
        $id = $response['id'];
        $response = $this->withHeaders($this->login($email))
            ->json('DELETE','comments/'.$id);
        $response->assertStatus(204);
    }


    /**
     * Тест админ может удалять комментарии
     */
    public function testDeleteCommentsAdmin():void
    {
        $email = User::find(2)['email'];
        $response = $this->createComments($email,'testText')->original;
        $id = $response['id'];
        $response = $this->withHeaders($this->login('admin@mail.ru'))
            ->json('Delete','comments/'.$id);
        $response->assertStatus(204);
    }
}
