<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupNewsPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_news', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('news_id');
        });

        Schema::table('group_news', function (Blueprint $table) {
            $table->foreign('group_id')->references('id')->on('groups')
                ->onDelete('cascade');
            $table->foreign('news_id')->references('id')->on('news')
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
        Schema::dropIfExists('group_news');
    }
}
