@extends('layouts.app_master_frontend')
@section('css')
    <?php $style = file_get_contents('css/product_detail_insights.min.css');echo $style;?>
@stop
<style>
    .number-empty, .number-empty:hover {
        cursor: not-allowed;
    }
</style>
@section('content')
    <div class="container">
        <div class="breadcrumb">
            <ul>
                <li>
                    <a itemprop="url" href="/" title="Home"><span itemprop="title">Home</span></a>
                </li>
                <li>
                    <a itemprop="url" href="{{ route('get.product.list') }}" title="Sản phẩm"><span
                            itemprop="title">{{ __('product.bre') }}</span></a>
                </li>

                <li>
                    <a itemprop="url" href="" title="Đồng hồ Diamond D"><span itemprop="title">{{ $product->pro_name }}</span></a>
                </li>

            </ul>
        </div>
        <div class="card">
            <div class="card-body info-detail">
                <div class="left">
                    @if (isset($images) && $images->count())
                        <div class="slider-pro" id="my-slider">
                            <div class="sp-slides">
                                @foreach($images as $item)
                                    <div class="sp-slide">
                                        <img class="sp-image" src="{{ pare_url_file($item->pi_slug) }}" alt="">
                                    </div>
                                @endforeach
                            </div>
                            <div class="sp-thumbnails">
                                @foreach($images as $item)
                                    <div class="sp-slide">
                                        <img class="sp-thumbnail" src="{{ pare_url_file($item->pi_slug) }}" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ route('get.product.detail',$product->pro_slug . '-'.$product->id ) }}" title="" class="">
                            <img alt="" style="max-width: 100%" src="{{ pare_url_file($product->pro_avatar) }}" class="lazyload">
                        </a>
                    @endif
                </div>
                <div class="right" id="product-detail" data-id="{{ $product->id }}">
                    <h1>{{  $product->pro_name }}</h1>
                    @if ( $product->pro_number <= 0 )
                        <p class="text-danger" style="font-size: 14px;margin-bottom: 5px;background: #dedede;padding: 5px;">{{ __('product.price_number') }}</p>
                    @endif
                    <div class="right__content">
                        <div class="info">

                            <div class="prices">
                                @if ($product->pro_sale)
                                    <p>{{ __('product.listed_price') }}:
                                        <span class="value">{{ number_format($product->pro_price,0,',','.') }} đ</span>
                                    </p>
                                    @php
                                        $price = ((100 - $product->pro_sale) * $product->pro_price)  /  100 ;
                                    @endphp
                                    <p>
                                        {{ __('product.price') }}: <span
                                            class="value price-new">{{  number_format($price,0,',','.') }} đ</span>
                                        <span class="sale">-{{  $product->pro_sale }}%</span>
                                    </p>
                                @else
                                    <p>
                                        {{ __('product.price') }}: <span class="value price-new">{{  number_format($product->pro_price,0,',','.') }} đ</span>
                                    </p>
                                @endif
                                <p>
                                    <span>{{ __('product.view') }} :&nbsp</span>
                                    <span>{{ $product->pro_view }}</span>
                                </p>
                                    <p>
                                    <span>Số lượng còn :&nbsp</span>
                                    <span>{{ $product->pro_number }}</span>
                                </p>
                            </div>
                            @if (!\Auth::id())
                                <p style="color:red">Đăng nhập để mua hàng</p>
                            @endif
                            <div class="btn-cart">
                                <a href="{{ route('get.shopping.add', $product->id) }}" title="" class="muangay {{ !\Auth::id() ? 'js-show-login' : '' }} {{ $product->pro_number <= 0 ? 'number-empty' : '' }}">
                                    <span>{{ __('product.buy_now') }}</span>
                                    <span>{{ __('product.hotline') }}: 1800.6005</span>
                                </a>
                                <a href="{{ route('ajax_get.user.add_favourite', $product->id) }}"
                                   title="{{ __('product.add_favourite') }}"
                                   class="muatragop  {{ !\Auth::id() ? 'js-show-login' : 'js-add-favourite' }}">
                                    <span>{{ __('product.add_favourite') }}</span>
                                    <span>{{ __('product.name_fav') }}</span>
                                </a>
                            </div>
                            <div class="infomation">
                                <h2 class="infomation__title">{{ __('product.info') }}</h2>
                                <div class="infomation__group">

                                    <div class="item">
                                        <p class="text1">{{ __('product.info_category') }}:</p>
                                        <h3 class="text2">
                                            @if (isset($product->category->c_name))
                                                <a href="{{  route('get.category.list', $product->category->c_slug).'-'.$product->pro_category_id }}">{{ $product->category->c_name }}</a>
                                            @else
                                                "[N\A]"
                                            @endif
                                        </h3>
                                    </div>
                                    @if (!empty($attribute))
                                        @foreach($attribute as $key => $attr)
                                            @if (!empty($attr))
                                                <div class="item" style="background: none;padding: 0">
                                                    @foreach($attr as  $k => $at)
                                                        @if (in_array($k, $attributeOld))
                                                            <p class="text1" style="padding-top: 5px;padding-left: 7px;">{{ $key }}:</p>
                                                            <h3 class="text2">{{ $at['atb_name'] }}</h3>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content" style="margin-bottom: 10px;">
{{--                <ul class="nav" style="margin-bottom: 15px;padding-top: 10px;">--}}
{{--                    <li><a href="" class="tab-item active" data-id="#tab-content">{{ __('product.tab_content') }}</a></li>--}}
{{--                    <li><a href="" class="tab-item" data-id="#tab-rating">{{ __('product.tab_rating') }}</a></li>--}}
{{--                </ul>--}}
                <div class="tab-item-content active" id="tab-content">
                    <div class="" style="margin-bottom: 10px">
                        {!! $product->pro_content !!}
                    </div>
                </div>
                <div class="tab-item-content" id="tab-rating" style="display: block">
                    @include('frontend.pages.product_detail.include._inc_ratings')
                </div>
{{--                <div class="tab-item-content" id="tab-content">--}}
{{--                    @include('frontend.pages.product_detail.include._inc_ratings')--}}
{{--                </div>--}}
            </div>


            <div class="comments">
                <div class="form-comment">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="productId" value="{{ $product->id }}">
                        <div class="form-group">
                            <textarea placeholder="..." name="comment" class="form-control" id="" cols="30" rows="5"></textarea>
                        </div>
                        <div class="footer">
                            <p>
                                <a href="" title="Gửi ảnh"  class="js-update-image"><i class="la la-camera"></i> {{ __('product.send_photos') }}</a>
                                <input type="file" class="js-input-image" id="document" name="images[]" multiple style="opacity: 0;display: none" >
                                <a href="">{{ __('product.posting_regulations') }}</a>
                            </p>
                            <button class=" {{ \Auth::id() ? 'js-save-comment' : 'js-show-login' }}">{{ __('product.submit_comment') }}</button>
                        </div>
                        <div class="gallery"></div>
                    </form>
                </div>
                @include('frontend.pages.product_detail.include._inc_list_comments')
            </div>

        </div>
        <div class="card-body product-des">
            <div class="left">
                <div class="tabs">
                    <div class="tabs__content">
                        <div class="product-five">
                            <div class="bot js-product-5 owl-carousel owl-theme owl-custom">
                                @foreach($productsSuggests as $product)
                                    <div class="item">
                                        @include('frontend.components.product_item',['product' => $product])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right">
            </div>
        </div>
    </div>
@stop
@section('script')
    <script>
		var ROUTE_COMMENT = '{{ route('ajax_post.comment') }}';
		var URL_CAPTCHA = '{{ route('ajax_post.captcha.resume') }}'
    </script>
    <script src="{{ asset('js/product_detail.js') }}" type="text/javascript"></script>

@stop
