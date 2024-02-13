<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'tags' => Tag::all()->pluck('value')->sort()->values()->toArray(),
        ]);
    }
}
