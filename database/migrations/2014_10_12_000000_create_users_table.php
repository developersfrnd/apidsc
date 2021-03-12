<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('role')->default('0')->comment('0 => Customer, 1=> Model');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('profilePicture')->nullable();
            $table->date('dob')->nullable();
            $table->integer('gender')->default(0)->comment('0 => Female, 1=> Male');
            $table->integer('status')->default(1)->comment('0 => Inactive, 1=> Active');
            $table->integer('isProfilePublished')->default(1)->comment('0 => No, 1=> Yes');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->text('categories')->nullable();
            $table->text('languages')->nullable();
            $table->string('body')->nullable();
            $table->string('ethnicity')->nullable();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->string('hairColor')->nullable();
            $table->string('hairLength')->nullable();
            $table->string('eyeColor')->nullable();
            $table->string('orientation')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
