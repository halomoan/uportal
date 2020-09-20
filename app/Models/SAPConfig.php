<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SAPConfig extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'sapconfig';
    public $timestamps = false;
}
