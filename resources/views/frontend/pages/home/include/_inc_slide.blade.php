@php
    $display_menu = config('layouts.component.cate.home.display');
@endphp
<div class="component-slide">
    <div id="content-slide">
        <div id="slider">
            <div class="imageSlide js-banner owl-carousel">
                @foreach($slides as $item)
                    <div>
                        <a href="{{ $item->sd_link }}" title="{{ $item->sd_title }}">
                            <img alt="Đồ án tốt nghiệp" src="{{ pare_url_file($item->sd_image) }}"  style="max-width: 100%;height: 300px;" class="" />
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
{{--    --}}
{{--    @if (config('layouts.pages.home.slide.with') == 'full')--}}
{{--        <div id="content-slide">--}}

{{--            <div class="spinner">--}}
{{--                <div class="rect1"></div>--}}
{{--                <div class="rect2"></div>--}}
{{--                <div class="rect3"></div>--}}
{{--                <div class="rect4"></div>--}}
{{--                <div class="rect5"></div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @else--}}
{{--        <div class="container" style="display: flex">--}}
{{--            <div class="left" style="width: 250px">--}}

{{--            </div>--}}
{{--            <div class="right" style=" width: calc(100% - 250px);">--}}
{{--                --}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}
</div>

{{--<div id="slider">--}}
{{--    <div class="imageSlide js-banner owl-carousel">--}}
{{--        @foreach($slides as $item)--}}
{{--            <div>--}}
{{--                <a href="{{ $item->sd_link }}" title="{{ $item->sd_title }}">--}}
{{--                    <img alt="Đồ án tốt nghiệp" src="{{ pare_url_file($item->sd_image) }}"  style="max-width: 100%;height: 300px;" class="" />--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        @endforeach--}}
{{--    </div>--}}
{{--</div>--}}
