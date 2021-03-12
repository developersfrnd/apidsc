<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('galleries')) {
            Schema::create('galleries', function (Blueprint $table) {
                $table->id()->autoIncrement();
                $table->string('title', 255);
                $table->text('description')->nullable();
                $table->string('name', 255);
                $table->string('tag', 255);
                $table->integer('user_id');
                $table->integer('privacy')->default(0)->comment("0->public 1->private");
                $table->integer('mediaType')->default(0)->comment("0->images 1->videos");
                $table->string('size')->nullable();
                $table->string('height')->nullable();
                $table->string('width')->nullable();
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
        Schema::dropIfExists('galleries');
    }
}
