<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkDevices extends Model
{
    use HasFactory;

    protected $table = 'networkdevices';
    protected $primaryKey = 'device_id';

    protected $fillable = [
        'name',
        'tipe',
        'ip_address',
        'mac_address',
        'vlan_id',
        'lokasi',
        'keterangan',
        'status',
        'uptime',
    ];

    public function vlan()
    {
        return $this->belongsTo(Vlan::class, 'vlan_id', 'vlan_id');
    }

    public function logs()
    {
        return $this->hasMany(DeviceLog::class, 'device_id', 'device_id');
    }
}
