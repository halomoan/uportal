<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceH extends Model
{
    protected $table = 'InvoiceH';
    protected $hidden = [
        'ByUser', 'created_at'
    ];
    protected $fillable = ['CoCode', 'Month', 'Year', 'TotRecord', 'ByUser', 'Status'];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
