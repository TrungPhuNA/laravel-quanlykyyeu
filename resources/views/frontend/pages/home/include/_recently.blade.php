<div class="top">
    <a href="#" style="text-transform: none;" class="main-title">{{ __('product.view_recently') }}</a>
</div>
<div class="bot">
    @if (isset($products))
        @foreach($products as $product)
            <div class="item">
                @include('frontend.components.product_item',['product' => $product])
            </div>
        @endforeach
    @endif
</div>
