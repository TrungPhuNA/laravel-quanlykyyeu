@extends('layouts.app_master_frontend')
@section('css')
    <?php $style = file_get_contents('css/blog_detail.min.css');echo $style;?>
@stop
@section('content')
    <div class="container">
        <div class="breadcrumb">
            <ul>
                <li >
                    <a href="/" title="Home"><span itemprop="title">Home</span></a>
                </li>
                <li >
                    <a href="{{  route('get.blog.home') }}" title="Đồng hồ chính hãng"><span itemprop="title">Article</span></a>
                </li>
                <li >
                    <a href="javascript://" title="Đồng hồ chính hãng"><span itemprop="title">{{  $article->a_name }}</span></a>
                </li>
            </ul>
        </div>
        <div class="blog-main">
            <div class="left">
                <div class="post-detail">
                    <h1 class="post-detail__title">{{ $article->a_name }}</h1>
                    <p class="post-detail__intro">{{  $article->a_description }}</p>
                    <div class="post-detail__content">
                        {!! $article->a_content !!}
                    </div>
                    <div class="post-detail_suggest" style="margin: 10px 0">
                        <h3 >Related posts</h3>
                        <ul>
                            @foreach($articleSuggest as $key => $item)
                            <li><a href="{{ route('get.blog.detail',$item->a_slug.'-'.$item->id) }}">
                                <span >{{ ($key + 1) }}</span> {{ $item->a_name }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="right">
                @include('frontend.components.articles_hot_sidebar_top',['articles' => $articlesHotSidebarTop])
                @include('frontend.components.top_product',['products' => $productTopPay])
                @include('frontend.components.hot_article',['articles'  => $articlesHot])
            </div>
        </div>
    </div>
@stop
@section('script')
    <script src="{{ asset('js/blog_detail.js') }}" type="text/javascript"></script>
@stop