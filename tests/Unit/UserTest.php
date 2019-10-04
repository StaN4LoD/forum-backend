<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testGetUsers()
    {
        $response = $this->json('GET','/users/1');
        $response->assertOk();
        $res = $response->original;
        $this->assertEquals($res['id'],1);
    }
}
