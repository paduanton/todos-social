<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Resources\TodosResource;
use Tests\TestCase;
use App\Http\Controllers\API\TodosController;
use App\Todos;
use Mockery;
use Illuminate\Http\Request;

use App\Users;

class TodosTest extends TestCase
{

    public function testShouldCreateTodo()
    {
        /*
            It must have user with the id 1 in the database first
        */

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
                'created' => true,
            ])->assertJsonFragment([

            ])->assertJsonStructure([
                
            ]);
    }
}
