<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'start_time','room_id','description','is_completed','name'
    ];

    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('checked_in');
    }
    public function room() {
        return $this->belongsTo('App\Room');
    }
}

