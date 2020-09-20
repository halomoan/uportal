<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaconfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faconfig', function (Blueprint $table) {
            $table->id();
            $table->string('desc', 50);
            $table->string('sub1len', 3);
            $table->string('sub2len', 3);
            $table->string('sub3len', 3);
            $table->string('runlen', 3);
            //$table->timestamps();

            //$table->foreign('group_id')->references('id')->on('groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faconfig');
    }
}
