<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceLog extends Model
{
    use HasFactory;

    protected $table = 'device_logs';
    protected $primaryKey = 'device_logs_id';

    protected $fillable = [
        'device_id',
        'event',
        'old_value',
        'new_value',
        'changed_by',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function device()
    {
        return $this->belongsTo(NetworkDevices::class, 'device_id', 'device_id');
    }
}
