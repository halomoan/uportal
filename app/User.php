<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'urole', 'photo', 'company', 'last_login'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function news()
    {
        //return $this->hasMany(News::class);
        return $this->belongsToMany(News::class);
    }

    public function mynews()
    {
        return $this->hasMany(News::class);
    }

    public function readNews()
    {

        return $this->hasMany(readNews::class);
    }

    public function timelines()
    {
        return $this->belongsToMany(Timeline::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }
}
