@extends('layouts.app_master_user')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/user.min.css') }}">
@stop
@section('content')
    <section>
        <div class="title">Track your order #{{ $transaction->id }}</div>
        <div class="content">
            <h4>Order status</h4>
            <div class="progress">
                <p>Trạng thái <b>{{ $transaction->getStatus($transaction->tst_status)['name'] }}</b></p>
                <div class="progress-bar">
                    @foreach(config('shopping_cart.progress') as $key => $item)
                        <div class="progress-item {{ (int)$key < $transaction->tst_status ? 'active' : '' }}">
                            <span>{{ $item }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <h4>Your order includes</h4>
            @include('user.include._inc_list_product_order')
            <div>
                <a href="{{ route('get.user.transaction') }}" class="btn btn-light"><i class="fa fa-angle-double-left"></i> Back to the order</a>
            </div>
        </div>
    </section>
@stop
