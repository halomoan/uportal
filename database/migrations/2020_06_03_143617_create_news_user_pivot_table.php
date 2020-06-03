<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsUserPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_user', function (Blueprint $table) {
              //$table->id();
              $table->integer('user_id')->unsigned();
              $table->integer('news_id')->unsigned();
  
              $table->foreign('user_id')->references('id')->on('users')
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
        Schema::dropIfExists('news_user');
    }
}
