<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Permission;
use Tymon\JWTAuth\JWTAuth;
use Auth;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable,HasRoles,SoftDeletes;

    protected $guard_name = 'api';
    
    public function meetings()
    {
        return $this->belongsToMany('App\Meeting');
    }    
    public function visits()
    {
        return $this->hasMany('App\Visit');
    }
    public function type() {
        return $this->belongsTo('App\Type');
    }
    protected $fillable = [
        'name', 'email', 'password','surname','image', 'personal_id', 'jmbg','type_id', 'coffees','image_path','sub_folder'
    ];

    protected $hidden = [
        'remember_token','created_at', 'updated_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }
}
