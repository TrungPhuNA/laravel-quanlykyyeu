@extends('layouts.app_master_frontend')
@section('css')
    <?php $style = file_get_contents('css/cart.min.css');echo $style;?>
@stop
<style type="text/css">
    @media (max-width: 767px) {
        .name-product {
            width: 300px;
            white-space: normal;
        }
    }
</style>
@section('content')
    <div class="container cart">
        <div class="left">
            <div class="list">
                <div class="title">{{ __('cart.title_page_shopping') }}</div>
                <div class="list__content">
                    @if($shopping->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th style="width: 100px;"></th>
                                <th style="width: 30%">Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total Money</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($shopping as $key => $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('get.product.detail',\Str::slug($item->name).'-'.$item->id) }}"
                                           title="{{ $item->name }}" class="avatar image contain">
                                            <img alt="" src="{{ pare_url_file($item->options->image) }}"
                                                 class="lazyload">
                                        </a>
                                    </td>
                                    <td>
                                        <div style="" class="name-product">
                                            <a href="{{ route('get.product.detail',\Str::slug($item->name).'-'.$item->id) }}"><strong>{{ $item->name }}</strong></a>
                                        </div>
                                    </td>
                                    <td>
                                        <p><b>{{  number_format($item->price,0,',','.') }} đ</b></p>
                                        <p>

                                            @if ($item->options->price_old)
                                                <span style="text-decoration: line-through;">{{  number_format(number_price($item->options->price_old),0,',','.') }} đ</span>
                                                <span class="sale">- {{ $item->options->sale }} %</span>
                                            @endif
                                        </p>
                                    </td>
                                    <td>
                                        <div class="qty_number">
                                            <input type="number" min="1" class="input_quantity" disabled
                                                   name="quantity_14692" value="{{  $item->qty }}" id="">
                                            <p data-price="{{ $item->price }}"
                                               data-url="{{  route('ajax_get.shopping.update', $key) }}"
                                               data-id-product="{{  $item->id }}">
                                                <span class="js-increase">+</span>
                                                <span class="js-reduction">-</span>
                                            </p>
                                            <a href="{{  route('get.shopping.delete', $key) }}"
                                               class="js-delete-item btn-action-delete"><i class="la la-trash"></i></a>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="js-total-item">{{ number_format($item->price * $item->qty,0,',','.') }} đ</span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p style="float: right;margin-top: 20px;">Total Money : <b id="sub-total">{{ \Cart::subtotal(0) }}
                            đ</b></p>
                    @else
                        <a href="" style="    width: 100%;height: 100%;margin: 0 auto;text-align: center;display: flex;align-items: center;align-content: center;justify-content: center;">
                            <img style="width: 200px;height: 200px" src="{{ asset('images/404.gif') }}" alt="">
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="right">
            <div class="customer">
                <div class="title">{{ __('cart.title_page_form') }}</div>
                <div class="customer__content">
                    <form class="from_cart_register" id="from_cart_register" action="{{ route('post.shopping.pay') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name <span class="cRed">(*)</span></label>
                            <input name="tst_name" id="name" required="" value="{{ get_data_user('web','name') }}"
                                   type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone <span class="cRed">(*)</span></label>
                            <input name="tst_phone" id="phone" required="" value="{{ get_data_user('web','phone') }}"
                                   type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="address">Address <span class="cRed">(*)</span></label>
                            <input name="tst_address" id="address" required=""
                                   value="{{ get_data_user('web','address') }}" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email">Email <span class="cRed">(*)</span></label>
                            <input name="tst_email" id="email" required="" value="{{ get_data_user('web','email') }}"
                                   type="text" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="note">Note</label>
                            <textarea name="tst_note" id="note" cols="3" style="min-height: 100px;" rows="2"
                                      class="form-control"></textarea>
                        </div>
                        <div class="btn-buy">
                            <button class="buy1 btn btn-purple {{ \Auth::id() ? '' : 'js-show-login' }}"
                                    style="width: 100%;border-radius: 5px" type="submit" name="pay" value="transfer">
                                {{ __('cart.btn_pay.default') }}
                            </button>
                        </div>
                        @php
                            $totalMoney = str_replace(',','',\Cart::subtotal(0));
                        @endphp
{{--                        <div class="btn-buy" style="margin-top: 10px">--}}
{{--                            <button class="buy1 btn btn-pink {{ \Auth::id() ? '' : 'js-show-login' }} {{ $totalMoney > get_data_user('web','balance') ? 'js-popup-wallet' : '' }}"--}}
{{--                                    style="width: 100%;border-radius: 5px" type="submit" name="pay" value="online">--}}
{{--                                {{ __('cart.btn_pay.online') }} (Ngân lượng)--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                        <div class="btn-buy" style="margin-top: 10px">--}}
{{--                            <button class="buy1 btn btn-success js-paypal {{ \Auth::id() ? '' : 'js-show-login' }}"--}}
{{--                                    style="width: 100%;border-radius: 5px" type="submit" name="pay" value="paypal" data-url="{{ route('post.shopping.paypal') }}">--}}
{{--                                {{ __('cart.btn_pay.online') }} (Paypal)--}}
{{--                            </button>--}}
{{--                        </div>--}}
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
@section('script')
    <script src="{{ asset('js/cart.js') }}" type="text/javascript"></script>
@stop
