<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSapconfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sapconfig', function (Blueprint $table) {
            $table->id();
            $table->string('desc', 50);
            $table->string('ashost', 50);
            $table->string('sysnr', 2);
            $table->string('client', 3);
            $table->string('user', 10);
            $table->string('passwd', 15);
            $table->boolean('trace');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sapconfig');
    }
}
