<?xml version="1.0" encoding="UTF-8"?>
<rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" xmlns:turbo="http://turbo.yandex.ru" version="2.0">
    <channel>
        <title>Сердало</title>
        <link>https://serdalo.ru</link>
        <description>Общенациональная газета «Сердало» - главное печатное издание Республики Ингушетия, предоставляющее читателям наиболее полную, оперативную, надежную и объективную информацию.</description>
        @foreach($posts as $post)
            <item turbo="true">
                <title>{{ $post->title }}</title>
                <link>{{ route('post-single', $post->slug) }}</link>
                <descripion><![CDATA[{{ $post->description }}]]></descripion>
		 <figure>
			@if(isset($post->file))
                	<img url="{{ $post->file->full_path }}" type="{{$post->file->type . '/' . $post->file->extension}}"></img>
			@endif                
		 </figure>
                <pubDate>{{  $post->published_at }}</pubDate>
                <yandex:full-text>{{ $post->lead }}</yandex:full-text>
            </item>
        @endforeach
    </channel>
</rss>
