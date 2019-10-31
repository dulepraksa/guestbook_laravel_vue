<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as OriginalPermission;

class Permission extends OriginalPermission
{
    public $guard_name = 'api';
    
    protected $hidden = [
        'id', 'guard_name', 'created_at', 'updated_at', 'pivot'
    ];

}
