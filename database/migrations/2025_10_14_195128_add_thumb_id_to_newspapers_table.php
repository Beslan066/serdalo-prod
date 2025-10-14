<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThumbIdToNewspapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newspapers', function (Blueprint $table) {
            $table->unsignedBigInteger('thumb_id')->nullable()->after('file_id');
            $table->foreign('thumb_id')->references('id')->on('files')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('newspapers', function (Blueprint $table) {
            $table->dropForeign(['thumb_id']);
            $table->dropColumn('thumb_id');
        });
    }
}
