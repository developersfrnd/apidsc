<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('videos')) {
            Schema::create('videos', function (Blueprint $table) {
                $table->id()->autoIncrement();
                $table->integer('user_id');
                $table->string('title', 255);
                $table->text('description')->nullable();
                $table->string('name', 255);
                $table->integer('creditPoints')->default(0);
                $table->integer('privacy')->default(0)->comment("0->public 1->private");
                $table->time('duration')->nullable();
                $table->string('thumb')->nullable();
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
        Schema::dropIfExists('videos');
    }
}
