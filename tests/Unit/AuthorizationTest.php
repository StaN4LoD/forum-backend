<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\DatabaseSeed;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use DatabaseSeed;
    private function createUser()
    {
        $faker = \Faker\Factory::create('ru_RU');
        $user = [
            'name' => $faker->firstName,
            'email' => $faker->safeEmail,
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ];
        $response = $this->json('POST','/users/register', $user);
        return json_decode($response->getContent(),true);
    }

    public function testLoginUser()
    {
        $response = $this->json('POST', '/users/login', [
            'email' => 'admin@mail.ru',
            'password' => 'secret'
        ]);
        $response->assertOk();
        $response->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
    }

    public function testLoginResponse()
    {
        $response = $this->json('POST', '/users/login', [
            'email' => 'admin@mail.ru',
            'password' => 'secret'
        ]);
        $res = $response->original;
        $this->assertIsString($res['access_token']);
        $this->assertEquals($res['token_type'], 'bearer');
    }

    public function testRegisterUser()
    {
        $faker = \Faker\Factory::create('ru_RU');
        $user = [
            'name' => $faker->firstName,
            'email' => $faker->safeEmail,
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ];
        $response = $this->json('POST','/users/register', $user);
        $response->assertStatus(201);
        $response->assertJsonStructure(['user' => ['name','email','id'],'token']);
    }

    public function testRegisterUserFail()
    {
        $faker = \Faker\Factory::create('ru_RU');
        $user = [
            'name' => $faker->firstName,
            'email' => $faker->safeEmail,
            'password' => 'secret',
        ];
        $response = $this->json('POST','/users/register', $user);
        $response->assertStatus(400);
    }

    public function testUserInfoMe()
    {
        $user = $this->createUser();
        $response = $this->withHeader('authorization', 'Bearer '.$user['token'])->json('GET','/users/me');
        $response->assertOk();
        $response->assertJsonStructure(['id','name','email','role','created_at']);
    }

    public function testUserInfoMeFail()
    {
        $response = $this->json('GET','/users/me');
        $response->assertStatus(403);
    }

}
