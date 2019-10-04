<?php

namespace Tests\Feature;

use Tests\DatabaseSeed;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class PostsTest extends TestCase
{
    use DatabaseSeed;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRegistration()
    {

        $this->assertTrue(true);
    }


    public function testGetAllPosts()
    {
        $response = $this->json('GET','/posts?page=1');
        $response->assertJsonStructure(['data'=>
            ['0' => ['id','category_id','title','description','content','username','user_id']]]);
        $response->assertJsonFragment(['current_page'=>1]);
        $response->assertOk();
    }

    public function testGetPosts()
    {
        $response = $this->json('GET','/posts/1');
        $response->assertJsonStructure([
            'id','category_id','title','description','content','tags','views','created_at','canEdit'
        ]);
    }

    public function testPostOne()
    {
        $response = $this->json('GET','/posts/1')->original;
        $this->assertEquals($response['id'],1);
        $this->assertIsString($response['title']);
        $this->assertIsString($response['content']);
        if (time() <= strtotime($response['created_at'])+3600) {
            $this->assertTrue($response['canEdit']);
        } else {
            $this->assertFalse($response['canEdit']);
        }
    }

    public function testFailCreatePost()
    {
        $login = $this->json('POST','/users/login',[
            'email' => 'admin@mail.ru',
            'password' => 'secret'
        ])->original;
        $response = $this->json('POST','/posts',[
            'category_id' => 1,
            'title' => 'Тестовый заголовок',
            'content' => 'Текстовая запись для теста',
            'tags' => ['tag_test1','tag_test2']
        ]);
        $response->assertStatus(403);
    }

    public function testCreatePost()
    {
        $login = $this->json('POST','/users/login',[
            'email' => 'admin@mail.ru',
            'password' => 'secret'
        ])->original;
        $response = $this->withHeaders(['authorization'=>'Bearer '.$login['access_token']])->json('POST','/posts',[
            'category_id' => 1,
            'title' => 'Тестовый заголовок',
            'content' => 'Текстовая запись для теста',
            'tags' => ['tag_test1','tag_test2']
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure(['id','user_id','category_id','title','content','tags','created_at']);
    }

    public function testCreatePostWithDescription()
    {
        $login = $this->json('POST','/users/login',[
            'email' => 'admin@mail.ru',
            'password' => 'secret'
        ])->original;
        $response = $this->withHeaders(['authorization'=>'Bearer '.$login['access_token']])->json('POST','/posts',[
            'category_id' => 1,
            'title' => 'Тестовый заголовок',
            'content' => 'Текстовая запись для теста',
            'description' => 'Описание',
            'tags' => ['tag_test1','tag_test2']
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure(['id','user_id','category_id','title','description','content','tags','created_at']);
    }
}
