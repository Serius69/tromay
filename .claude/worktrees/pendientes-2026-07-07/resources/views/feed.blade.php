<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>Tromay Casa de Cambio — Noticias</title>
        <link>{{ url('/') }}</link>
        <description>Últimas noticias y actualizaciones de tasas de cambio.</description>
        <language>es-bo</language>
        <lastBuildDate>{{ now()->toRfc2822String() }}</lastBuildDate>
        <atom:link href="{{ route('feed.rss') }}" rel="self" type="application/rss+xml"/>
        @foreach($items as $item)
        <item>
            <title><![CDATA[{{ $item->name }}]]></title>
            <link>{{ route('noticia.show', $item->id) }}</link>
            <guid isPermaLink="true">{{ route('noticia.show', $item->id) }}</guid>
            <description><![CDATA[{{ Str::limit(strip_tags($item->description), 500) }}]]></description>
            @if($item->category)
            <category><![CDATA[{{ $item->category }}]]></category>
            @endif
            <pubDate>{{ $item->date_publication?->toRfc2822String() ?? now()->toRfc2822String() }}</pubDate>
        </item>
        @endforeach
    </channel>
</rss>
