@if($base['page']['title'] != null && $base['server']['path'] != '/' && $base['server']['path'] != rtrim($base['site']['subsite-folder'], '/')) {!! $base['page']['title'] !!} -@endif {!! $base['site']['title'] !!}@if(!empty(config('base.surtitle'))) - {{ config('base.surtitle') }}@endif - Wayne State University
