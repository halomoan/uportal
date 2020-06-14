<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'from', 'title', 'tltype_id', 'readmore'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function body()
    {
        return $this->hasMany(TLBody::class, 'timeline_id', 'id');
    }

    public function type()
    {
        return $this->hasMany(TLType::class, 'type', 'id');
    }
}
