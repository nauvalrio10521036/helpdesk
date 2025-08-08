<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'report';
    protected $primaryKey = 'report_id';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'lokasi',
        'user_id',
        'device_id',
        'status',
        'prioritas',
        'attachment',
        'time_report',
        'time_finished',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function device()
    {
        return $this->belongsTo(NetworkDevices::class, 'device_id', 'device_id');
    }
}
