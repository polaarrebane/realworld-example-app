<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $body
 * @property User $author
 * @property Article $article
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $author_id
 * @property int $article_id
 */
class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['body'];

    protected $visible = ['id', 'body'];

    /**
     * @return BelongsTo<User,Comment>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * @return BelongsTo<Article,Comment>
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
