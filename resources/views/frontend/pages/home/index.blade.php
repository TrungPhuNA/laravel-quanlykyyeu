@extends('layouts.app_master_frontend')
@section('css')
    <?php $style = file_get_contents('css/home_insights.min.css');echo $style;?>
@stop
{{--<style>--}}
{{--    #menu-main {--}}
{{--        display: block !important;--}}
{{--    }--}}
{{--</style>--}}
@section('content')
    @include('frontend.pages.home.include._inc_slide')
    <div class="container" id="before-slide">
        <div class="product-one">
            <div class="top">
                <a href="#" title="" class="main-title main-title-2">{{ __('product.selling') }}</a>
            </div>
            <div class="bot">
                @if ($event1)
                    <div class="left">
                        <div class="image">
                            <a href="{{  $event1->e_link }}" title="" class="{{ $event1->e_name }} image" target="_blank">
                                <img style="height: 310px;" class="lazyload lazy" alt="{{ $event1->e_name }}" src="{{  asset('images/no-image') }}-{{ config('app.name') }}.jpg"  data-src="{{  pare_url_file($event1->e_banner) }}" />
                            </a>
                        </div>
                    </div>
                @endif
                <div class="right js-product-one owl-carousel owl-theme owl-custom">
                    @foreach($productsPay as $product)
                        <div class="item">
                            @include('frontend.components.product_item',[ 'product' => $product])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @if ($event2)
            @include('frontend.pages.home.include._inc_flash_sale')
        @endif
        <div class="product-three">
            <div class="top">
                <a href="#" title="" class="main-title main-title-2">{{ __('product.new') }}</a>
            </div>
            <div class="bot">
                <div class="left">
                    <div class="image">
                        @if (isset($event3->e_link))
                            <a href="{{  $event3->e_link }}" title="" class="{{ $event3->e_name }}" target="_blank">
                                <img style="height: 310px;" class="lazyload lazy" alt="{{ $event3->e_name }}" src="{{  asset('images/no-image') }}-{{ config('app.name') }}.jpg"  data-src="{{  pare_url_file($event3->e_banner) }}" />
                            </a>
                        @endif
                    </div>
                </div>
                <div class="right js-product-one owl-carousel owl-theme owl-custom">
                    @if (isset($productsNew))
                        @foreach($productsNew as $product)
                            <div class="item">
                                @include('frontend.components.product_item',['product' => $product])
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
{{--        @if (!detectDevice()->isMobile())--}}
{{--            <div class="container">--}}
{{--                <div class="banner" style="display: flex">--}}
{{--                    <div class="item" style="flex: 0 0 33.33333%">--}}
{{--                        <a href="">--}}
{{--                            <img src="{{ asset('images/banner/banner-1.png') }}" alt="">--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                    <div class="item" style="flex: 0 0 33.33333%">--}}
{{--                        <a href="">--}}
{{--                            <img src="{{ asset('images/banner/banner-2.png') }}" alt="">--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                    <div class="item" style="flex: 0 0 33.33333%">--}}
{{--                        <a href="">--}}
{{--                            <img src="{{ asset('images/banner/banner-1.png') }}" alt="">--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endif--}}
        <div class="product-two">
            <div class="top">
                <a href="#" class="main-title main-title-2">{{ __('product.hot') }}</a>
            </div>
            <div class="bot">
                @if (isset($productsHot))
                    @foreach($productsHot as $product)
                    {{-- {{ dd($product) }} --}}
                        <div class="item">
                            @include('frontend.components.product_item',['product' => $product])
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="product-two" id="product-recently"></div>
        <div id="product-by-category"></div>
{{--        @include('frontend.pages.home.include._inc_article')--}}
    </div>
@stop

@section('script')
    <script>
		var routeRenderProductRecently  = '{{ route('ajax_get.product_recently') }}';
		var routeRenderProductByCategory  = '{{ route('ajax_get.product_by_category') }}';
    </script>
    <script type="text/javascript">
		<?php $js = file_get_contents('js/home.js');echo $js;?>
    </script>
@stop
