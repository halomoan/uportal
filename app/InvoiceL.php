<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceL extends Model
{
    protected $table = 'InvoiceL';
    public $timestamps = false;
    protected $primaryKey = 'invoiceh_id';
    protected $fillable = ['invoiceh_id', 'text'];
}
