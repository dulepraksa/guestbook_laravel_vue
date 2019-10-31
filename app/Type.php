<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'id'
    ];

    public function users() {
        return $this->hasMany('App\User');
    }
}

