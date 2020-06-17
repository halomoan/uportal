<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'from', 'title', 'type', 'link', 'linktext', 'param1'
    ];

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function group()
    {
        return $this->belongsToMany(Group::class);
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
