<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GdprLog extends Model
{
    protected $fillable = [
        'user', 'gdpr_sensitive_data', 'date_and_time', 'ip_adress', 'requested_user_id'
    ];
}
