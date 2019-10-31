<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'description','arrived_at','left_at','user_id','planned','planned_time','image'
    ];

    public function user() {
       return $this->belongsTo('App\User');
    }
}
