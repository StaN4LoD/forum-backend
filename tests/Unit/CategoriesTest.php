<?php
/**
 * Created by PhpStorm.
 * User: dmitrybosykh
 * Date: 22/12/2018
 * Time: 19:44
 */

namespace Tests\Feature;

//use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DatabaseSeed;
use Tests\TestCase;


class CategoriesTest extends TestCase
{
    use DatabaseSeed;

//    use DatabaseMigrations;
    public function testGetAllCategories()
    {
        $response = $this->json('GET','/categories');
        $response->assertJsonStructure([0 => ['id','name','status','avatar', 'description', 'created_at', 'updated_at']]);
        $response->assertOk();
    }

    public function testGetCategory()
    {
        $response = $this->json('GET','/categories/1');
        $response->assertJsonStructure(['id','name','status','avatar', 'description', 'created_at', 'updated_at']);
        $response->assertOk();
    }

    public function testCategoryOne()
    {
        $response = $this->json('GET', '/categories/1')->original;
        $this->assertIsNumeric($response['id'], 1);
        $this->assertIsString($response['name']);
        $this->assertIsString($response['description']);
    }

    public function testCreateCategories()
    {
        $login = $this->json('POST','/users/login',[
            'email' => 'admin@mail.ru',
            'password' => 'secret'
        ])->original;
        $response = $this->withHeaders(['authorization'=>'Bearer '.$login['access_token']])
            ->json('POST','/categories',[
            'name' => 'Тестовое имя',
            'description' => 'Текстовая запись для теста',
            'avatar' => 'Тестовый аватар',
            'status' => 1,
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure(['id','name','status','avatar', 'description', 'created_at', 'updated_at']);
    }

    public function testUpdateCategories()
    {
        $login = $this->json('POST','/users/login',[
            'email' => 'admin@mail.ru',
            'password' => 'secret'
        ])->original;
        $response = $this->withHeaders(['authorization'=>'Bearer '.$login['access_token']])
            ->json('PUT','/categories/1',[
                'name' => 'Тестовое имя',
                'description' => 'Текстовая запись для теста',
                'avatar' => 'Тестовый аватар',
                'status' => 1,
            ]);
        $response->assertStatus(201);
        $response->assertJsonStructure(['id','name','status','avatar', 'description', 'created_at', 'updated_at']);
    }
}
