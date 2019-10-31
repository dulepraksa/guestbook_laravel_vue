<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'name','contract','user_id','contract_path','sub_folder'
    ];
}
