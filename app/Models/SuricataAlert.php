<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuricataAlert extends Model
{
    protected $fillable = [
        'src_ip', 'dest_ip', 'protocol', 'message', 'priority'
    ];

    protected $dates = ['created_at', 'updated_at'];

}
