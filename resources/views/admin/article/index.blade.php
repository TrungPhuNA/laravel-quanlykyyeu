@extends('layouts.app_master_admin')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>{{  __('admin_article.article.title_index')}}</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{  route('admin.article.index') }}"> Article</a></li>
            <li class="active"> List </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <form class="form-inline">
                    <input type="text" class="form-control" value="{{ Request::get('id') }}" name="id" placeholder="ID">
                    <input type="text" class="form-control" value="{{ Request::get('name') }}" name="name" placeholder="Name ...">
                    <select name="menu" class="form-control" >
                        <option value="0">Chọn menu</option>
                        @foreach($menus as $item)
                            <option value="{{ $item->id }}" {{ Request::get('menu') == $item->id ? "selected='selected'" : "" }}>{{  $item->mn_name }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
                    <a href="{{ route('admin.article.create') }}" class="btn btn-primary">{{  __('admin_article.article.btn_add')}} <i class="fa fa-plus"></i></a>
                </form>
            </div>
                <div class="box-body">
                   <div class="col-md-12">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th style="width: 10px">STT</th>
                                    <th style="width: 10px">ID</th>
                                    <th style="width: 25%">Name</th>
                                    <th>Category</th>
                                    <th>Avatar</th>
                                    <th>Hot</th>
                                    <th>Status</th>
                                    <th>Time</th>
                                    <th>Action</th>
                                </tr>

                            </tbody>
                            @if (isset($articles))
                            
                                    @foreach($articles as $key => $article)
                                        <tr>
                                            <td>{{ (($articles->currentPage() - 1) * $articles->perPage()) + ( $key + 1)  }}</td>
                                            <td>{{ $article->id }}</td>
                                            <td>{{ $article->a_name }}</td>
                                            <td>
                                                <span class="label label-success">{{ $article->menu->mn_name ?? "[N\A]" }}</span>
                                            </td>
                                            <td>
                                                <img src="{{ pare_url_file($article->a_avatar) }}" style="width: 80px;height: 80px">
                                            </td>

                                            <td>
                                                @if ($article->a_hot == 1)
                                                    <a href="{{ route('admin.article.hot', $article->id) }}" class="label label-info">Hot</a>
                                                @else
                                                    <a href="{{ route('admin.article.hot', $article->id) }}" class="label label-default">None</a>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($article->a_active == 1)
                                                    <a href="{{ route('admin.article.active', $article->id) }}" class="label label-info">Active</a>
                                                @else
                                                    <a href="{{ route('admin.article.active', $article->id) }}" class="label label-default">Hide</a>
                                                @endif
                                            </td>
                                            <td>{{  $article->created_at }}</td>
                                            <td style="width: 120px">
                                                <a href="{{ route('admin.article.update', $article->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i> Edit</a>
                                                <a href="{{  route('admin.article.delete', $article->id) }}" class="btn btn-xs btn-danger js-delete-confirm"><i class="fa fa-trash"></i> Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    {!! $articles->appends($query ?? [])->links() !!}
                </div>
                <!-- /.box-footer-->
            </div>
            <!-- /.box -->
        </div>
    </section>
    <!-- /.content -->
@stop
