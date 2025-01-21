<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'gender',
        'phone',
        'email',
        'password',
        'type',
        'code',
        'role',
        'created_at',
        'updated_at',

    ];
    public function scopeSelection($q)
    {
        return $this->select([
            'id',
            'name',
            'gender',
            'phone',
            'email',
            'type',
            'code',
            'role',
            'created_at',
            'updated_at',
        ]);
    }

    protected $hidden = [
        'password', // إخفاء كلمة المرور
        'remember_token',
    ];
    public function getRoleAttribute($val)
    {
        if (is_array($val)) {
            return $val; // إذا كان الحقل بالفعل مصفوفة، قم بإعادته كما هو
        }

        return ($val != null) ? json_decode($val, true) : [];
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


}
