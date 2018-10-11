{{--
    $news => array // [['news_id', 'slug', 'title']]
    $site => array // ['subsite-folder']
    $heading => string // 'News'
    $url => string '/news/'
    $link_text => string // 'More news'
--}}
<h2>{{ $heading ?? 'News' }}</h2>

<ul class="list-reset">
    @foreach($news as $item)
        <li class="mb-4">
            <a href="{{ $item['full_link'] }}" class="underline hover:no-underline font-normal">
                {{ $item['title'] }}
            </a>
        </li>
    @endforeach
</ul>

<a href="/{{ $url ?? config('base.news_listing_route').'/' }}" class="block my-4 underline hover:no-underline">{{ $link_text ?? 'More news' }}</a>
