<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TLBody extends Model
{

    public $timestamps = false;

    protected $fillable = ['body'];

    protected $table = 'tlbody';

    public function timeline()
    {
        return $this->belongsTo(Timeline::class);
    }
}
