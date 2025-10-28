<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // ✅ penting untuk createToken

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = ['name','email','password','role_id'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
