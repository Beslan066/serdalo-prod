<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLitsalonArticleTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('litsalon_article_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('litsalon_article_id');
            $table->string('language');
            $table->string('title')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('subtitle', 255)->nullable();
            $table->string('dates', 255)->nullable();
            $table->string('short_description', 255)->nullable();
            $table->text('description')->nullable();
            $table->text('biography')->nullable();
            $table->text('about_creativity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('litsalon_article_translations');
    }
}
