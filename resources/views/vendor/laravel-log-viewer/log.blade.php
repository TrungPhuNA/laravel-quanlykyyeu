@extends('layouts.app_master_admin')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
@stop
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Log System</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"> Log </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <div class="col sidebar mb-3">
                    <div class="list-group div-scroll">
                        @foreach($files as $file)
                            <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}" style="display: inline-block;color: #0c83e7"
                               class="list-group-item @if ($current_file == $file) llv-active @endif">
                                {{$file}}
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="box-body table-container">
                    @if ($logs === null)
                        <div>
                            Log file >50M, please download it.
                        </div>
                    @else
                        <table id="table-log" class="table table-striped" data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
                            <thead>
                            <tr>
                                @if ($standardFormat)
                                    <th>Level</th>
                                    <th>Context</th>
                                    <th>Date</th>
                                @else
                                    <th>Line number</th>
                                @endif
                                <th>Content</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($logs as $key => $log)
                                <tr data-display="stack{{{$key}}}">
                                    @if ($standardFormat)
                                        <td class="nowrap text-{{{$log['level_class']}}}">
									<span class="fa fa-{{{$log['level_img']}}}"
                                          aria-hidden="true"></span>&nbsp;&nbsp;{{$log['level']}}
                                        </td>
                                        <td class="text">{{$log['context']}}</td>
                                    @endif
                                    <td class="date">{{{$log['date']}}}</td>
                                    <td class="text">
                                        @if ($log['stack'])
                                            <button type="button"
                                                    class="float-right expand btn btn-outline-dark btn-sm mb-2 ml-2"
                                                    data-display="stack{{{$key}}}">
                                                <span class="fa fa-search"></span>
                                            </button>
                                        @endif
                                        {{{$log['text']}}}
                                        @if (isset($log['in_file']))
                                            <br/>{{{$log['in_file']}}}
                                        @endif
                                        @if ($log['stack'])
                                            <div class="stack" id="stack{{{$key}}}"
                                                 style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    @endif
                    <div class="">
                        @if($current_file)
                            <a href="?dl={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                                <span class="fa fa-download"></span> Download file
                            </a>
                            -
                            <a id="clean-log"
                               href="?clean={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                                <span class="fa fa-sync"></span> Clean file
                            </a>
                            -
                            <a id="delete-log"
                               href="?del={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                                <span class="fa fa-trash"></span> Delete file
                            </a>
                            @if(count($files) > 1)
                                -
                                <a id="delete-all-log"
                                   href="?delall=true{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                                    <span class="fa fa-trash-alt"></span> Delete all files
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
@stop
@section('script')
    <style>
        .dataTables_filter, #table-log_paginate {
            text-align: right;
        }
    </style>
    <!-- Datatables -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.table-container tr').on('click', function () {
                $('#' + $(this).data('display')).toggle();
            });

            let $tableLog = $('#table-log');
            $tableLog.DataTable({
                "order": [$tableLog.data('orderingIndex'), 'desc'],
                "stateSave": true,
                "stateSaveCallback": function (settings, data) {
                    window.localStorage.setItem("datatable", JSON.stringify(data));
                },
                "stateLoadCallback": function (settings) {
                    var data = JSON.parse(window.localStorage.getItem("datatable"));
                    if (data) data.start = 0;
                    return data;
                }
            });
            $('#delete-log, #clean-log, #delete-all-log').click(function () {
                return confirm('Are you sure?');
            });
        });
    </script>
@stop
