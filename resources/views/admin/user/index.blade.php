@extends('layouts.app_master_admin')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>{{ __('admin_user.user.title_index') }}</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{  route('admin.user.index') }}"> User</a></li>
            <li class="active"> List </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <form class="form-inline">
{{--                    <input type="text" class="form-control" value="{{ Request::get('id') }}" name="id" placeholder="ID">--}}
                    <input type="text" class="form-control" value="{{ Request::get('name') }}" name="name" placeholder="Name ...">
                    <input type="text" class="form-control" value="{{ Request::get('email') }}" name="email" placeholder="Email ...">
                    <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
                </form>
                <div class="box-body">
                   <div class="col-md-12">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th style="width: 10px">Stt</th>
                                    <th style="width: 10px">Code</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Time</th>
                                    <th>Action</th>
                                </tr>
                                @if (isset($users))
                                    @foreach($users as $key => $user)
                                        <tr>
                                            <td>{{ ($key + 1) }}</td>
                                            <td>{{ $user->code }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td>{{ $user->created_at }}</td>
                                            <td>
{{--                                                <a href="{{ route('admin.user.transaction', $user->id) }}" class="btn btn-xs btn-primary js-show-transaction"> N??? c???n thu</a>--}}
                                                <a href="{{  route('admin.user.delete', $user->id) }}" class="btn btn-xs btn-danger js-delete-confirm"><i class="fa fa-trash"></i> Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    {!! $users->appends($query)->links() !!}
                </div>
                <!-- /.box-footer-->
            </div>
            <!-- /.box -->
        </div>
    </section>
    <!-- /.content -->
    <div class="modal fade" id="modal-transaction" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">??</span></button>
                    <h4 class="modal-title">N??? c???n thu t??? kh??ch h??ng</h4>
                </div>
                <div class="modal-body" id="content-transaction">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary pull-right" data-dismiss="modal">????ng</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@stop

@section('script')
    <script>
        $(function () {
            $(".js-show-transaction").click(function (event) {
                event.preventDefault();
                let URL = $(this).attr('href');
                $.ajax({
                    url: URL,
                }).done(function( results ) {
                    $("#modal-transaction").modal({show: true});
                    $("#content-transaction").html(results.html)
                });
            })
            $("body").on("click","table .js-success-transaction", function(event){
                let URL = $(this).attr('href');
                let $this = $(this);
                event.preventDefault();
                $.ajax({
                    url: URL,
                }).done(function( results ) {
                    if (results.code)
                    {
                        $this.parents('tr').remove();
                    }
                });
            });
        })
    </script>
@stop