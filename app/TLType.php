<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TLType extends Model
{
    protected $table = 'tltype';
    public $timestamps = false;
    protected $fillable = ['name'];
}
