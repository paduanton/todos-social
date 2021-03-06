<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{

    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id');
            $table->foreignId('todos_id');
            $table->foreignId('parent_comments_id')->nullable();
            $table->string('description');
            $table->timestampsTz(0);
            $table->softDeletesTz('deleted_at', 0);
            $table->foreign('users_id')->references('id')->on('users');
            $table->foreign('todos_id')->references('id')->on('todos');
            $table->foreign('parent_comments_id')->references('id')->on('comments');
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
