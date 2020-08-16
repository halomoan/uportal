<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TLType extends Model
{
    protected $table = 'tltype';
    public $timestamps = false;
    protected $fillable = ['name'];
}
