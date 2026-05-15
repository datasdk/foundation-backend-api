<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->uuid('uid')->unique()->nullable(false);
                $table->string('image', 2048)->nullable();
                $table->string('username', 100)->nullable();
                $table->string('first_name')->nullable();
                $table->string('middle_name', 200)->nullable();
                $table->string('last_name')->nullable();
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password')->nullable();
                $table->string('type', 100)->default("default");
                $table->boolean('active')->default(true);
                $table->string('remember_token', 100)->nullable();
                $table->timestamp('lastLoggedIn')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable("users"))
        Schema::dropIfExists('users');
    }
}
