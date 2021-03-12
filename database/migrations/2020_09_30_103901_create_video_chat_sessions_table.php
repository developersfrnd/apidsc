<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoChatSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('model id');
            $table->text('token');
            $table->string('channel');
            $table->integer('subscriber_id')->comment('In case of individual video session')->nullable();
            $table->integer('subscriber_token')->comment('In case of individual video session')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video_chat_sessions');
    }
}
