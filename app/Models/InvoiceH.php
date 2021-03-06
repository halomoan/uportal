<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceH extends Model
{
    protected $table = 'InvoiceH';
    protected $hidden = [
        'ByUser', 'created_at'
    ];
    protected $fillable = ['CoCode', 'Month', 'Year', 'NoOfRec', 'TotRec', 'ByUser', 'Status'];

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'invoiceh_id', 'id');
    }
}
