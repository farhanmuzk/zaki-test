<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject; // Tambahkan import ini

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject // Implementasikan JWTSubject
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Implementasi metode getJWTIdentifier
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Mengembalikan ID pengguna
    }

    /**
     * Implementasi metode getJWTCustomClaims
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return []; // Kembalikan klaim tambahan jika diperlukan
    }
}
