<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('invoiceh_id');
            $table->string('invno');
            $table->string('invdate');
            $table->string('year');
            $table->string('desc')->nullable();
            $table->double('amount', 15, 2)->default(0.00);
            $table->string('filename');
            $table->boolean('unread')->default(0);
            $table->boolean('published')->default(0);
            $table->timestamps();

            $table->index('user_id');
            $table->index('invoiceh_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreign('invoiceh_id')->references('id')->on('InvoiceH')
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
        Schema::dropIfExists('invoices');
    }
}
