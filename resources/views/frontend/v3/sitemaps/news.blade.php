<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    @foreach($posts as $post)
        <url>
            <loc>https://serdalo.ru/{{ $post->slug }}</loc>
            <lastmod>{{ $post->updated_at->toIso8601String() }}</lastmod>
            <news:news>
                <news:publication>
                    <news:name>Сердало</news:name>
                    <news:language>ru</news:language>
                </news:publication>
                <news:publication_date>{{ $post->published_at->format('Y-m-d\TH:i:s\Z') }}</news:publication_date>
                <news:title>{{ $post->title }}</news:title>
                <news:keywords>{{ $post->tags->pluck('title')->implode(', ') }}</news:keywords>
                @if($post->file)
                    <image:image>
                        <image:loc>{{ $post->file->full_preview_path }}</image:loc>
                        <image:title>{{ $post->title }}</image:title>
                    </image:image>
                @endif
            </news:news>
        </url>
    @endforeach
</urlset>
