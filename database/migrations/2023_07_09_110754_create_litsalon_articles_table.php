<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLitsalonArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('litsalon_articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title', 255);
            $table->string('generation', 255)->nullable();
            $table->unsignedBigInteger('file_id')->nullable();
            $table->string('subtitle', 255)->nullable();
            $table->string('dates', 255)->nullable();
            $table->string('short_description', 255)->nullable();
            $table->text('description')->nullable();
            $table->text('biography')->nullable();
            $table->text('about_creativity')->nullable();
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
        Schema::dropIfExists('litsalon_articles');
    }
}
