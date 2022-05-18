<style>
    @if (config('layouts.component.footer.color_text'))
        #footer ul li a {
            color: {{ config('layouts.component.footer.color_text') }};
        }
        #footer .title {
            {{ config('layouts.component.footer.title') }};
        }
    @endif
</style>
<div id="footer" style="background: {{ config('layouts.component.footer.background') }}">
    <div class="container footer">
        <div class="footer__left">
            <div class="top">
                <div class="item">
                    <div class="title">{{ __('footer.col1.title') }}</div>
                    <ul>
{{--                        <li>--}}
{{--                            <a href="{{ route('get.blog.home') }}">{{ __('footer.col1.article') }}</a>--}}
{{--                        </li>--}}
                        <li>
                            <a href="{{ route('get.product.list') }}">{{ __('footer.col1.product') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('get.register') }}">{{ __('footer.col1.register') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('get.login') }}">{{ __('footer.col1.login') }}</a>
                        </li>
                    </ul>
                </div>
                <div class="item">
                    <div class="title">{{ __('footer.col2.title') }}</div>
                    <ul>
                        @if (isset($menus))
                            @foreach($menus as $menu)
                                <li>
                                    <a title="{{ $menu->mn_name }}"
                                        href="{{ route('get.article.by_menu',$menu->mn_slug.'-'.$menu->id) }}">
                                        {{ $menu->mn_name }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                        <li><a href="{{ route('get.contact') }}">{{ __('footer.col2.contact') }}</a></li>
                    </ul>
                </div>
                <div class="item">
                    <div class="title">{{ __('footer.col4.title') }}</div>
                    <ul>
                        <li><a href="#">{{ __('footer.col4.address1') }}: <b>{{ __('footer.col4.val1') }}</b></a></li>
                        <li><a href="#">SĐT: <b> 0988.111.222</b></a></li>
                        <br>
                        <li><a href="#">{{ __('footer.col4.address2') }}: <b>{{ __('footer.col4.val2') }}</b></a></li>
                        <li><a href="#">SĐT: <b>0989.111.892</b></a></li>
                    </ul>
                </div>
                <div class="item">
                    <div class="title">{{ __('footer.col3.title') }}</div>
                    <ul>
                        <li><a href="{{ route('get.static.shopping_guide') }}">{{ __('footer.col3.shopping_guide') }}</a></li>
                        <li><a href="{{  route('get.static.return_policy') }}">{{ __('footer.col3.return_policy') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container footer">
{{--        <div class="col-5" style="margin-right: 50px;">--}}
{{--            <div class="footer-widget">--}}
{{--                <div class="title">{{ __('footer.contact.title') }}</div>--}}
{{--                <ul class="list-menu" style="display:block">--}}
{{--                    <li>{{ __("footer.contact.phone") }}: 0902720570</li>--}}
{{--                    <li>{{ __("footer.contact.email") }}: webmypham.vn@gmail.com</li>--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="col-4 footer__left">
            <div class="bot">
                <div class="social">
                    <div class="title">{{ __('footer.social.title') }}</div>
                    <p>
                        <a href="" class="fa fa fa-youtube"></a>
                        <a href="" class="fa fa-facebook-official"></a>
                        <a href="" class="fa fa-twitter"></a>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-4 footer__left">
            <div class="bot">
                <div class="social">
                    <div class="title">Language</div>
                    <p>
                        @if(config('app.locale') == 'vi')
                            <a style="width: 100px;display: flex;align-items: center" href="{{ route('get.language','en') }}"><img style="margin-right: 10px;" src="{{ asset('icon/en.png') }}" alt=""> English</a>
                        @else
                            <a style="width: 100px;display: flex;align-items: center" href="{{ route('get.language','vi') }}"><img style="margin-right: 10px;" src="{{ asset('icon/vi.png') }}" alt=""> Vietnam</a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v6.0&appId=3205159929509308&autoLogAppEvents=1"></script> --}}

