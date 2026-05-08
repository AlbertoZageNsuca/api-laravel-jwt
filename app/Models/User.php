<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject; // <--- Importante

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password','role'
    ];

    public function isAdmin()
    {
    return $this->role === 'admin';
    }

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Método obrigatório: retorna o ID do usuário para o Token
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // Método obrigatório: permite adicionar dados extras ao Token
    public function getJWTCustomClaims()
    {
        return [];
    }
}
