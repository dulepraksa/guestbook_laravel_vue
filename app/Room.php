<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Room extends Model
{
    use SoftDeletes;
    
    public function meeting() {
        return $this->hasOne('App/Meeting');
    }

    protected $fillable = ['name'];

}
