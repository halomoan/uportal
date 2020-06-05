<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('read_news', function (Blueprint $table) {
            
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('news_id');
            
        
            $table->primary(array('user_id', 'news_id'));
            $table->index('user_id');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('read_news');
    }
}
