<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChatMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
   
        if(!Schema::hasTable("chat_conversations"))
        Schema::create("chat_conversations", function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('name', 250); // eksisterende
            $table->string('title', 250)->nullable();        // ny
            $table->text('description')->nullable();         // ny
            
            $table->boolean('private')->default(true);
            $table->boolean('direct_message')->default(false);
            $table->text('data')->nullable();
            $table->timestamps();
        });


        if(!Schema::hasTable("chat_participation"))
        Schema::create("chat_participation", function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('conversation_id')->unsigned();
            $table->bigInteger('messageable_id')->unsigned();
            $table->string('messageable_type');
            $table->text('data')->nullable();
            $table->timestamps();

            $table->unique(['conversation_id', 'messageable_id', 'messageable_type'], 'participation_index');

            $table->foreign('conversation_id')
                ->references('id')
                ->on("chat_conversations")
                ->onDelete('cascade');
        });

        
        if(!Schema::hasTable("chat_messages"))
        Schema::create("chat_messages", function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('body');
            $table->bigInteger('conversation_id')->unsigned();
            $table->bigInteger('participation_id')->unsigned()->nullable();
            $table->string('type')->default('text');
            $table->text('data')->nullable();
            $table->timestamps();

            $table->foreign('participation_id')
                ->references('id')
                ->on("chat_participation")
                ->onDelete('set null');

            $table->foreign('conversation_id')
                ->references('id')
                ->on("chat_conversations")
                ->onDelete('cascade');
        });
        
        if(!Schema::hasTable("chat_message_notifications"))
        Schema::create("chat_message_notifications", function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('message_id')->unsigned();
            $table->bigInteger('messageable_id')->unsigned();
            $table->string('messageable_type');
            $table->bigInteger('conversation_id')->unsigned();
            $table->bigInteger('participation_id')->unsigned();
            $table->boolean('is_seen')->default(false);
            $table->boolean('is_sender')->default(false);
            $table->boolean('flagged')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['participation_id', 'message_id'], 'participation_message_index');

            $table->foreign('message_id')
                ->references('id')
                ->on("chat_messages")
                ->onDelete('cascade');

            $table->foreign('conversation_id')
                ->references('id')
                ->on("chat_conversations")
                ->onDelete('cascade');

            $table->foreign('participation_id')
                ->references('id')
                ->on("chat_participation")
                ->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        if(Schema::hasTable("chat_message_notifications"))
        Schema::dropIfExists("chat_message_notifications");

        if(Schema::hasTable("chat_messages"))
        Schema::dropIfExists("chat_messages");

        if(Schema::hasTable("chat_participation"))
        Schema::dropIfExists("chat_participation");

        if(Schema::hasTable("chat_conversations"))
        Schema::dropIfExists("chat_conversations");

    }
}
