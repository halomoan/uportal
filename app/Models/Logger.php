<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logger extends Model
{
    protected $table = 'logger';
    public $timestamps = false;
    protected $primaryKey = 'user_id';
    protected $fillable = ['user_id', 'category', 'text'];
}
