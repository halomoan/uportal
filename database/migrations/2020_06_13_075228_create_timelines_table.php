<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimelinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timelines', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('from')->nullable();
            $table->string('title');
            $table->string('link', 20)->nullable();
            $table->string('linktext', 20)->nullable();
            $table->string('param1', 20)->nullable();
            $table->unsignedTinyInteger('type');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timelines');
    }
}
