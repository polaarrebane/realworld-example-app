<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $bio
 * @property string $image
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected const FAVORITE_TABLE = 'favorite';

    protected const FOLLOW_TABLE = 'follow';

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

    /**
     * @return BelongsToMany<User>
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follow', 'following_id', 'user_id');
    }

    /**
     * @return BelongsToMany<User>
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follow', 'user_id', 'following_id');
    }

    public function isFavorited(Article $article): bool
    {
        return DB::table(self::FAVORITE_TABLE)
            ->where([
                'user_id' => $this->id,
                'article_id' => $article->id,
            ])
            ->exists();
    }

    public function isFollowing(User $user): bool
    {
        return DB::table(self::FOLLOW_TABLE)
            ->where([
                'user_id' => $this->id,
                'following_id' => $user->id,
            ])
            ->exists();
    }

    /**
     * @return HasMany<Article>
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    /**
     * @return BelongsToMany<Article>
     */
    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'favorite', 'user_id', 'article_id');
    }
}
