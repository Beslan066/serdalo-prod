<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
    @foreach($posts as $post)
        @php
            try {
                $publishedAt = \Carbon\Carbon::parse($post->published_at);
                $publicationDate = $publishedAt->format('Y-m-d\TH:i:s\Z');
            } catch (Exception $e) {
                // Если дата некорректна, используем текущее время
                $publicationDate = now()->format('Y-m-d\TH:i:s\Z');
            }
        @endphp
        <url>
            <loc>{{ route('post-single', $post->slug) }}</loc>
            <news:news>
                <news:publication>
                    <news:name>Сердало</news:name>
                    <news:language>ru</news:language>
                </news:publication>
                <news:publication_date>{{ $publicationDate }}</news:publication_date>
                <news:title>{{ $post->title }}</news:title>
                @if($post->tags->isNotEmpty())
                    <news:keywords>{{ $post->tags->pluck('title')->implode(', ') }}</news:keywords>
                @endif
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
