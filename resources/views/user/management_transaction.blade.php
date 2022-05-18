@extends('layouts.app_master_user')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/user.min.css') }}">
    <style>


    </style>
@stop
@section('content')
    <section>
        <div class="title">Balance fluctuation history</div>
        <div class="content">
            <table class="table">
                <thead>
                    <tr>
                        <th >#</th>
                        <th >Code GD</th>
                        <th style="width: 15%">Volatility</th>
                        <th class="text-center">Total Money</th>
                        <th class="text-center">Time</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payHistories as $pay)
                        <tr>
                            <th scope="row">#{{ $pay->id }}</th>
                            <th>{{ $pay->ph_code }}</th>
                            @if ($pay->ph_credit)
                                <th class="text-center">
                                    <span class="manage-money">
                                        <i class="fa fa-long-arrow-up"></i>{{ number_format($pay->ph_credit,0,',','.') }} <sup>đ</sup>
                                    </span>
                                </th>
                            @else
                                <th class="text-center">
                                    <span class="manage-money">
                                        <i class="fa fa-long-arrow-down"></i>{{ number_format($pay->ph_debit,0,',','.') }} <sup>đ</sup>
                                    </span>
                                </th>
                            @endif
                            <th>
                                {{ number_format($pay->ph_balance,0,',','.') }} đ
                            </th>
                            <th>{{  $pay->created_at }}</th>
                            <th>
                                <span><i class="fa fa-circle"></i> Success</span>
                            </th>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </section>
@stop
