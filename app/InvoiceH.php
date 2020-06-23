<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceH extends Model
{
    protected $table = 'invoiceh';    
    protected $fillable = [ 'CoCode','Month','Year','TotRecord','ByUser','Status' ];

}
