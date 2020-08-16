<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{

    protected $fillable = [
        'user_id', 'invoiceh_id', 'invno', 'invdate', 'year', 'desc', 'amount', 'filename', 'unread', 'published'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoiceh()
    {
        return $this->belongsTo(InvoiceH::class);
    }
}
