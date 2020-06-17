<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupTimelinePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_timeline', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('timeline_id');
            $table->unsignedBigInteger('news_id')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('news_id');

            $table->foreign('group_id')->references('id')->on('groups')
                ->onDelete('cascade');
            $table->foreign('timeline_id')->references('id')->on('timelines')
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
        Schema::dropIfExists('group_timeline');
    }
}
