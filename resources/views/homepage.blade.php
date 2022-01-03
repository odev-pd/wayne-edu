@extends('components.content-area')

@section('content')
    @include('components.page-title', ['title' => $base['page']['title']])

    <div class="content">
        {!! $base['page']['content']['main'] !!}
    </div>

    @if(!empty($articles['data']) || !empty($events))
        <div class="row -mx-4 flex flex-wrap">
            @if(!empty($articles['data']))
                <div class="w-full md:w-1/2 px-4">
                    <h2>News articles</h2>
                    @include('components/article-listing', ['articles' => $articles['data'], 'url' => ($base['site']['subsite-folder'] !== null ? $base['site']['subsite-folder'] : '').config('base.news_listing_route').'/'])
                </div>
            @endif

            @if(!empty($events))
                <div class="w-full md:w-1/2 px-4">
                    <h2>Events</h2>
                    @include('components/events-listing', ['events' => $events, 'cal_name' => !empty($base['site']['events']['path']) ? $base['site']['events']['path'] : null])
                </div>
            @endif
        </div>
    @endif
@endsection
