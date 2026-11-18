<?php

namespace App\Http\Controllers;

use App\Models\Joke;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $jokes = Joke::where('status', 'published')->latest('updated_at')->get(['id', 'updated_at']);

        return response()
            ->view('seo.sitemap', ['jokes' => $jokes])
            ->header('Content-Type', 'application/xml');
    }
}
