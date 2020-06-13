<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'from', 'title', 'tltype_id', 'readmore'
    ];

    public function body()
    {
        return $this->hasMany(TLBody::class);
    }

    public function type()
    {
        return $this->hasMany(TLType::class, 'id', 'type');
    }
}
