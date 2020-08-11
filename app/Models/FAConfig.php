<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FAConfig extends Model
{

    public $timestamps = true;

    protected $fillable = ['group_id', 'sub1len', 'sub2len', 'sub3len', 'runlen'];

    protected $table = 'faconfig';
    protected $primaryKey = 'group_id';
}
