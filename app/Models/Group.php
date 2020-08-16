<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_enabled', 'is_default'

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function news()
    {
        //return $this->belongsToMany(News::class, 'group_news');
        return $this->belongsToMany(News::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function timelines()
    {
        return $this->belongsToMany(Timeline::class);
    }
}
