<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FAConfig extends Model
{

    public $timestamps = false;

    protected $fillable = ['desc', 'sub1len', 'sub2len', 'sub3len', 'runlen'];
    protected $table = 'faconfig';
}
