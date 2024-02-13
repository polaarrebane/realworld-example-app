<?php

use App\Models\Tag;

use function Pest\Laravel\getJson;

const API_TAGS_GET = 'api.tags.get';

it('should successfully retrieve all existing tags', function () {
    $tags = Tag::factory()->count(rand(3, 100))->create()->pluck('value');
    $response = getJson(route(API_TAGS_GET));
    expect($response['tags'])->toContain(...$tags);
});
