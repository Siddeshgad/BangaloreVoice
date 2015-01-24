<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		 Schema::create('users', function($table) {
            $table->increments('id');
		    $table->string('firstname', 20);
		    $table->string('lastname', 20);
		    $table->string('email', 100)->unique();
		    $table->string('password', 64);
		    $table->string('superuser', 1)->default(0);
		    $table->longText('avatar');
		    $table->string('ip', 20);
		    $table->string('status', 1)->default(1);
		    $table->string('activation_key', 200);
		    $table->string('verify_email', 1)->default(1);
		    $table->string('remember_token', 100);
		    $table->timestamps();
		    $table->softDeletes();
		    //$table->primary('id');
        });

		Schema::create('posts', function($table) {
            $table->increments('id');
		    $table->string('title', 50);
		    $table->longText('description');
		    $table->longText('image');
		    $table->double('location');
		    $table->integer('upvotes');
		    $table->integer('downvotes');
		    $table->integer('parent_id');
		    $table->string('status', 1);
		    $table->longText('response');
		    $table->timestamps();
		    $table->softDeletes();
		    //$table->primary('id');
        });		 

		Schema::create('upvote', function($table) {
            $table->increments('id');
		    $table->foreign('id')->references('id')->on('users');
		    $table->foreign('id')->references('id')->on('posts');
		    $table->timestamps();
		    $table->softDeletes();
		    //$table->primary('id');
        });		 

        Schema::create('downvote', function($table) {
            $table->increments('id');
		    $table->foreign('id')->references('id')->on('users');
		    $table->foreign('id')->references('id')->on('posts');
		    $table->timestamps();
		    $table->softDeletes();
		    //$table->primary('id');
        });		 


	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		 Schema::drop('users');
		 Schema::drop('posts');
		 Schema::drop('upvote');
		 Schema::drop('downvote');
	}

}
