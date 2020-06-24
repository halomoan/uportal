<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceLTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('InvoiceL', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedBigInteger('invoiceh_id');
            $table->string('text');            
            $table->timestamp('created_at')->useCurrent();

            $table->index('invoiceh_id');

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
        Schema::dropIfExists('InvoiceL');
    }
}
