<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

/**
 * @property string $value
 */
class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['value'];

    /**
     * @return BelongsToMany<Article>
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class);
    }

    /**
     * @return Attribute<string,string>
     */
    protected function value(): Attribute
    {
        return Attribute::make(
            get: static fn (string $value) => $value,
            set: static fn (string $value) => Str::lower($value),
        );
    }
}
