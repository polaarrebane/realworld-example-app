<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $bio
 * @property string $image
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'bio',
        'image',
    ];

    /**
     * @var array<string,string>
     */
    protected $attributes = [
        'bio' => '',
        'image' => '',
    ];

    /**
     * @var array<int,string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var array<int,string>
     */
    protected $visible = [
        'username',
        'email',
        'bio',
        'image',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * @return string
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array<string,string>
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
