<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFauserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fauser', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->boolean('active');
            $table->string('companies', 50);

            $table->index('user_id');
        });

        Schema::table('fauser', function (Blueprint $table) {
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
        Schema::dropIfExists('fauser');
    }
}
