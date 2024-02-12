<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    protected ?Collection $tags = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->realText(),
            'description' => fake()->realText(),
            'body' => implode(' ', fake()->paragraphs()),
            'author_id' => User::factory(),
        ];
    }

    public function withTags(Collection $tags): self
    {
        return $this->afterCreating(function (Article $article) use ($tags) {
            $article->tags()->sync($tags->pluck('id'));
        });
    }

    public function withTag(Tag $tag): self
    {
        return $this->withTags(collect([$tag]));
    }
}
