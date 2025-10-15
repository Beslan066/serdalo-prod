<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
    @foreach($posts as $post)
        <url>
            <loc>{{ route('post-single', $post->slug) }}</loc>
            <news:news>
                <news:publication>
                    <news:name>Название вашего СМИ</news:name>
                    <news:language>{{ App::getLocale() }}</news:language>
                </news:publication>
                <news:publication_date>{{ $post->published_at->format('Y-m-d\TH:i:s\Z') }}</news:publication_date>
                <news:title>{{ $post->title }}</news:title>
                @if($post->tags->isNotEmpty())
                    <news:keywords>{{ $post->tags->pluck('title')->implode(', ') }}</news:keywords>
                @endif
            </news:news>
        </url>
    @endforeach
</urlset>
