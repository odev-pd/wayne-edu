@extends('components.content-area')

@section('content')
    @include('components.page-title', ['title' => $page['title']])

    <div class="content">
        {!! $page['content']['main'] !!}

        <table class="table-stack" aria-label="Example table with fake contact information">
            <thead>
                <tr>
                    <th scope="col">First name</th>
                    <th scope="col">Last name</th>
                    <th scope="col">Email</th>
                </tr>
            </thead>

            <tbody>
                @for ($i = 0; $i < 10; $i++)
                    <tr valign="top">
                        <td>{{ $faker->firstName }}</td>
                        <td>{{ $faker->lastName }}</td>
                        <td>{{ $faker->email }}</td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <a href="#table-stack" class="button" onclick="document.querySelector('pre.table-stack').classList.toggle('hidden');">See table code</a>

        <pre class="table-stack hidden" style="background: #EAEAEA; margin-bottom: 10px; overflow: scroll;">
        {!! htmlspecialchars('
<table class="table-stack" aria-label="Example table">
    <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
        ') !!}
        </pre>
    </div>
@endsection
