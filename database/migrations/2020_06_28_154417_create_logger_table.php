<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoggerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logger', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedBigInteger('user_id');
            $table->char('category', 10);
            $table->string('text');
            $table->timestamp('created_at')->useCurrent();

            $table->index('user_id');

            $table->foreign('user_id')->references('id')->on('users')
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
        Schema::dropIfExists('logger');
    }
}
