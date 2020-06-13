<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTlbodyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tlbody', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            //$table->id();
            $table->unsignedBigInteger('timeline_id');
            $table->text('body')->nullable();
            $table->index('timeline_id');
        });

        Schema::table('tlbody', function (Blueprint $table) {
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
        Schema::dropIfExists('tlbody');
    }
}
