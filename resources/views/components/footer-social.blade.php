{{--
    $social => array // [['title','link']]
--}}
<div class="bg-green-darker py-6">
    <div class="row flex justify-center items-center">
            @foreach($social as $item)
                <a href="{{ $item['link'] }}" target="_blank" rel="noopener" class="flex justify-center items-center h-10 w-10 md:h-14 md:w-14 mx-1 md:mx-6 text-green-lightest fill-current bg-green rounded-full transition transition-property-bg transition-delay-none hover:bg-green-lighter hover:text-white">
                    @svg($item['title'])
                </a>
            @endforeach
        </div>
</div>
