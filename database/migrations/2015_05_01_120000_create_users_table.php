<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table)
        {
            // Engine
            $table->engine = 'InnoDB';

            // Fields
            $table->increments('id');
            $table->string('name', 255)->index();
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('role', 255)->nullable()->index();
            $table->boolean('valid')->default(true);
            $table->timestamp('last_login')->nullable();
            $table->string('last_known_ip', 255)->nullable();
            $table->rememberToken()->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }

}
