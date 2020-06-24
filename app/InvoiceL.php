<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceL extends Model
{
    protected $table = 'InvoiceL';
    public $timestamps = false;
    protected $fillable = ['invoiceh_id', 'text'];
}
