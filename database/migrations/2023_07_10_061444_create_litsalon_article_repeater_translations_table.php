<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLitsalonArticleRepeaterTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('litsalon_article_repeater_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('litsalon_article_repeater_id');
            $table->string('language');
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('litsalon_article_repeater_translations');
    }
}
