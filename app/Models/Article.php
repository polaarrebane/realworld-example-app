<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property string $body
 * @property User $author
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Article extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = ['title', 'description', 'body'];

    /**
     * @var array<string,string>
     */
    protected $attributes = [
        'description' => '',
        'body' => '',
    ];

    /**
     * @var array<int,string>
     */
    protected $visible = [
        'description',
        'body',
        'title',
        'slug',
        'favoritesCount',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return BelongsToMany<Tag>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * @return BelongsTo<User, Article>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * @return HasMany<Comment>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'article_id');
    }

    /**
     * @return BelongsToMany<User>
     */
    public function favorited(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorite', 'article_id', 'user_id');
    }

    public function scopeFiltered(
        Builder $query,
        ?string $tag = null,
        ?string $author = null,
        ?string $favorited = null
    ): void {
        if ($tag) {
            $query->whereHas('tags', static fn ($query) => $query->where('value', '=', $tag));
        }

        if ($author) {
            $query->whereHas('author', static fn ($query) => $query->where('username', '=', $author));
        }

        if ($favorited) {
            $query->whereRelation('favorited', 'username', '=', $favorited);
        }
    }

    public function scopeFeed(Builder $query, User $user): void
    {
        $query->whereIn('author_id', $user->following->pluck('id'));
    }

    /**
     * @return Attribute<int, int>
     */
    protected function favoritesCount(): Attribute
    {
        return Attribute::make(
            get: fn () => DB::table('favorite')->where('article_id', $this->id)->count(),
        );
    }
}
