@extends('partials.content-area')

@section('content')
    <h1 class="page-title">{{ $page['title'] }}</h1>

    {!! $page['content']['main'] !!}

    @if(isset($accordion_page) && count($accordion_page) > 0)
        @include('components.accordion', ['items' => $accordion_page])
    @endif

<a class="button" onclick="$('pre.accordions').toggleClass('hide');">See Accordion Code</a>

    <pre class="accordions hide" style="background: #EAEAEA; margin-bottom: 10px; overflow: scroll;">
    {{ htmlspecialchars('
<ul class="accordion">
    <li class="is-active">
        <a href="#panel1a">Accordion 1</a>
        <div id="panel1a">
            <p>Panel 1. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        </div>
    </li>
    <li>
        <a href="#panel2a">Accordion 2</a>
        <div id="panel2a">
            <p>Panel 2. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        </div>
    </li>
    <li>
        <a href="#panel3a">Accordion 3</a>
        <div id="panel3a">
            <p>Panel 3. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        </div>
    </li>
</ul>
    ') }}
    </pre>
@endsection