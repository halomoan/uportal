<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected  $primaryKey = 'CoCode';
    protected $table = 'company';
    public $timestamps = false;
    protected $fillable = [ 'CoCode','Name' ];
}
