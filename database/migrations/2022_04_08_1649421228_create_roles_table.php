<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    public function up()
    {

        if(!Schema::hasTable('roles'))
        Schema::create('roles', function (Blueprint $table) {

        $defaultGuard = config('auth.defaults.guard');    
        
		$table->bigInteger('id',20)->unsigned();
		$table->string('key_id',500)->nullable();
		$table->string('name')->nullable();
		$table->text('description')->nullable();
		$table->string('guard_name')->default($defaultGuard);
		$table->boolean('active')->default('1');
		$table->timestamps();

        });
    }

    public function down()
    {
        if(Schema::hasTable("roles"))
        Schema::dropIfExists('roles');
    }
}