<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vlan extends Model
{
    use HasFactory;

    protected $table = 'vlan';

    protected $primaryKey = 'vlan_id';

    protected $fillable = [
        'name_vlan',
        'subnet',
        'name_port',
        'deskripsi',
    ];

    // Relasi: 1 VLAN memiliki banyak perangkat
    public function devices()
    {
        return $this->hasMany(NetworkDevices::class, 'vlan_id', 'vlan_id');
    }
}
