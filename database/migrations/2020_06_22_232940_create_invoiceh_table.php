<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicehTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('InvoiceH', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->char('CoCode', 4);
            $table->char('Year', 4);
            $table->char('Month',2);
            $table->unsignedInteger('TotRecord');
            $table->string('ByUser', '50')->nullable();
            $table->char('Status', 3)->nullable();
            $table->timestamps();

            $table->index(['CoCode', 'Year']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('InvoiceH');
    }
}
