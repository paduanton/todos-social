<?php

namespace Tests\Feature;

use App\Http\Resources\TodosResource;
use Tests\TestCase;
use App\Users;
use App\Todos;


class TodosTest extends TestCase
{

    public function testShouldCreateTodo()
    {
        $user = factory(Users::class)->create();

        $response = $this->actingAs($user, 'api')
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->json('POST', '/v1/users/1/todos', [
                'title' => 'Lorem ipsum',
                'description' => 'Lorem ipsum',
                'completed' => true
            ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'title' => 'Lorem ipsum',
            ])->assertJsonFragment([
                'title' => 'Lorem ipsum',
                'description' => 'Lorem ipsum',
                'completed' => true
            ])->assertJsonStructure([
                'id',
                'users_id',
                'title',
                'description',
                'completed',
                'images',
                'comments',
                'created_at',
                'updated_at',
            ]);
    }

    public function testShouldUpdateTodo()
    {
        /*
            It must have a todo with the id 1 in the database first
        */

        $user = factory(Users::class)->create();

        $response = $this->actingAs($user, 'api')
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->json('PUT', '/v1/todos/1', [
                'title' => 'Now, this is a story',
                'description' => 'Lorem ipsum',
                'completed' => false
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'title' => 'Now, this is a story',
            ])->assertJsonFragment([
                'title' => 'Now, this is a story',
                'description' => 'Lorem ipsum',
                'completed' => 0
            ])->assertJsonStructure([
                'id',
                'users_id',
                'title',
                'description',
                'completed',
                'images',
                'comments',
                'created_at',
                'updated_at',
            ]);
    }

    public function testShouldGetTodo()
    {
        /*
            It must have a todo with the id 1 in the database first
        */

        $user = factory(Users::class)->create();

        $response = $this->actingAs($user, 'api')
            ->withHeaders([
                'Accept' => 'application/json',
            ])->json('GET', '/v1/todos/1');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'users_id',
                'title',
                'description',
                'completed',
                'images',
                'comments',
                'created_at',
                'updated_at',
            ]);
    }

    public function testShouldDeleteTodo()
    {
        /*
            It must have a todo with the id 1 in the database first
        */

        $user = factory(Users::class)->create();

        $response = $this->actingAs($user, 'api')
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->json('DELETE', '/v1/todos/1');

        $response->assertNoContent($status = 204);
    }

    public function testShouldGetAllTodos()
    {
        $user = factory(Users::class)->create();

        $response = $this->actingAs($user, 'api')
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->json('GET', '/v1/todos');

        $response->assertStatus(200);
    }

    public function testShouldGetUnauthorized()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->json('GET', '/v1/todos');

        $response->assertUnauthorized();

    }

    public function testShouldNotGetTodo()
    {
        /*
            It must not have a todo with the id 1211332 in the database first
        */

        $user = factory(Users::class)->create();

        $response = $this->actingAs($user, 'api')
            ->withHeaders([
                'Accept' => 'application/json',
            ])->json('GET', '/v1/todos/1211332');

        $response->assertNotFound();
    }
}
