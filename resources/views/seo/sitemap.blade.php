{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ([route('home'), route('jokes.index'), route('leaderboard.index'), route('login'), route('register')] as $url)
    <url><loc>{{ $url }}</loc></url>
@endforeach
@foreach ($jokes as $joke)
    <url>
        <loc>{{ route('jokes.show', $joke) }}</loc>
        <lastmod>{{ $joke->updated_at->toAtomString() }}</lastmod>
    </url>
@endforeach
</urlset>
