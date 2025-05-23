<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLitsalonArticleRepeatersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('litsalon_article_repeaters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('litsalon_article_id');
            $table->string('type')->default('publications');//'publications', 'awards'
            $table->unsignedBigInteger('file_id')->nullable();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
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
        Schema::dropIfExists('litsalon_article_repeaters');
    }
}
