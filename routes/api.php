<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['todos-api', date(DATE_ISO8601), env('APP_ENV')];
});

Route::group(['prefix' => '/v1'], function () use ($router) {
    $router->get('/', function () {
        return ['todos-v1-api', date(DATE_ISO8601), env('APP_ENV')];
    });

    /*
        Unauthenticated Routes
    */

        // Todos

    $router->get('/todos/{title}/search', 'API\TodosController@search');
    $router->get('/todos/{id}', 'API\TodosController@show');

    /*
        Authentication Routes
    */

    $router->post('/login', 'API\AuthController@login');
    $router->post('/signup', 'API\AuthController@signup');

    Route::group(['middleware' => 'auth:api'], function () use ($router) {
        $router->post('/logout', 'API\AuthController@logout');

        /*
            Users Routes
        */

        $router->get('/users/{name}/search', 'API\UsersController@search');
        $router->get('/user/{username}', 'API\UsersController@getByUsername');
        $router->apiResource('/users', 'API\UsersController');
        $router->get('/user', function (Request $request) {
            return $request->user();
        });

        /*
            Todos Routes
        */

        $router->apiResource('/todos', 'API\TodosController');
        $router->get('/users/{usersId}/todos', 'API\TodosController@getTodosByUsersId');
        $router->post('/users/{usersId}/todos', 'API\TodosController@store');

        /*
            TodosImages Routes
        */

        $router->get('/images/{id}/todos', 'API\TodosImagesController@show');
        $router->delete('/images/{id}/todos', 'API\TodosImagesController@destroy');
        $router->get('/todos/{todosId}/images', 'API\TodosImagesController@index');
        $router->post('/todos/{todosId}/images', 'API\TodosImagesController@upload');
        $router->patch('/todos/{todosId}/images/{id}', 'API\TodosImagesController@update');

        /*
            ProfileImages Routes
        */

        $router->get('/images/{id}/users', 'API\ProfileImagesController@show');
        $router->delete('/images/{id}/users', 'API\ProfileImagesController@destroy');
        $router->get('/users/{usersId}/images', 'API\ProfileImagesController@index');
        $router->post('/users/{id}/images/', 'API\ProfileImagesController@upload');
        $router->get('/users/{usersId}/images', 'API\ProfileImagesController@index');
        $router->get('/users/{usersId}/thumbnail', 'API\ProfileImagesController@getThumbnail');
        $router->patch('/users/{usersId}/images/{id}', 'API\ProfileImagesController@update');

        /*
            Followers Routes
        */

        $router->get('/users/{id}/followers', 'API\FollowersController@getFollowers');
        $router->get('/users/{id}/following', 'API\FollowersController@getFollowing');
        $router->get('/users/{id}/friends', 'API\FollowersController@getFriends');
        $router->get('/users/{firstUsersId}/mutual/{secondUsersId}/following', 'API\FollowersController@getMutualFollowing');
        $router->get('/users/{firstUsersId}/mutual/{secondUsersId}/followers', 'API\FollowersController@getMutualFollowers');
        $router->post('/users/{firstUsersId}/follow/{secondUsersId}', 'API\FollowersController@follow');
        $router->delete('/users/{firstUsersId}/unfollow/{secondUsersId}', 'API\FollowersController@unfollow');

        /*
            Comments Routes
        */

        $router->apiResource('/comments', 'API\CommentsController');
        $router->get('/todos/{todosId}/comments', 'API\CommentsController@getCommentsByTodosId');
        $router->post('/users/{usersId}/todos/{todosId}/comments', 'API\CommentsController@store');

    });
});
