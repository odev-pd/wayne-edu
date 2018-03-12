{{--
    $news => array // [['news_id', 'slug', 'title']]
    $site => array // ['subsite-folder']
    $heading => string // 'News'
    $url => string '/news.php'
    $link_text => string // 'More news'
--}}
<h2>{{ $heading or 'News' }}</h2>

<ul class="listing">
    @foreach($news as $item)
        <li>
            <a href="/{{ ($site['subsite-folder'] !== null) ? $site['subsite-folder'] : '' }}news/{{ $item['slug'] }}-{{ $item['news_id'] }}">
                {{ $item['title'] }}
            </a>
        </li>
    @endforeach
</ul>

<a href="/{{ $url or 'news/' }}" class="more-link">{{ $link_text or 'More news' }}</a>
