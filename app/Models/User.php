<?php

// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $primaryKey = 'user_id';
    protected $table = 'user'; // sesuaikan nama tabel
    protected $fillable = ['name', 'username', 'password', 'role']; // kolom yang bisa diisi
    public $timestamps = true; // pastikan aktif kalau kamu punya created_at & updated_at

}


// class User extends Model
// {
//     protected $primaryKey = 'user_id';
//     protected $table = 'user'; // sesuaikan nama tabel
//     protected $fillable = ['name', 'username', 'password', 'role']; // kolom yang bisa diisi
//     public $timestamps = true; // pastikan aktif kalau kamu punya created_at & updated_at

// }

