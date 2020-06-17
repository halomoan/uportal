<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimelineUserPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timeline_user', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('timeline_id');
            $table->unsignedBigInteger('news_id')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('news_id');

            $table->foreign('user_id')->references('id')->on('users')
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
        Schema::dropIfExists('timeline_user');
    }
}
