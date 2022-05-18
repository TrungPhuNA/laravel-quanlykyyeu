@extends('layouts.app_master_admin')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Permission</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Account</a></li>
            <li><a href="#">Permission</a></li>
            <li class="active">Index</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <div class="box-title">
                            <form action="" class="form-inline" method="GET">
                                <input type="text" placeholder="ID" name="id" class="form-control">
                                <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
                                <a href="{{ route('admin.permission.create') }}" class="btn btn-info"><i class="fa fa-plus-circle"></i> Add</a>
                            </form>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Guard_name</th>
                                    <th>Description</th>
                                    <th>Create</th>
                                    <th>Action</th>
                                </tr>
                                @if (isset($permissions))
                                    @foreach($permissions as $permission)
                                        <tr>
                                            <td>{{ $permission->id }}</td>
                                            <td>{{ $permission->name }}</td>
                                            <td>{{ $permission->guard_name }}</td>
                                            <td>{{ $permission->description }}</td>
                                            <td>{{ $permission->created_at }}</td>
                                            <td>
                                                <a href="{{ route('admin.permission.update', $permission->id) }}" class="btn btn-xs btn-info"><i class="fa fa-pencil-square"></i> Edit</a>
                                                <a href="{{ route('admin.permission.delete', $permission->id) }}" class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i> Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
@endsection
