<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role_id', 'permissions'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $guarded = [];
    protected $hidden = ['password', 'remember_token'];
    
    // Wajib ditambahkan agar data JSON dari database otomatis menjadi Array
    protected $casts = [
        'permissions' => 'array',
    ];
    
    public function role() { return $this->belongsTo(Role::class); }
    public function orders() { return $this->hasMany(Order::class); }
}