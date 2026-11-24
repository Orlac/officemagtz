<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/** Нужна только механизму аутентификации Laravel (EloquentUserProvider). */
class User extends Authenticatable
{
    protected $fillable = ['login', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }
}
