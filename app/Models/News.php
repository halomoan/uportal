<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{

    protected $fillable = [
        'title', 'description', 'author', 'validFrom', 'validTo', 'showauthor', 'color'
    ];

    protected $hidden = [];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }
}
