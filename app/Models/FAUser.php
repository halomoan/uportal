<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FAUser extends Model
{

    public $timestamps = false;

    protected $fillable = ['user_id', 'active', 'companies',];
    protected $table = 'fauser';
}
