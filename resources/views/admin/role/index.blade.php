@extends('layouts.app_master_admin')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Roles</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Account</a></li>
            <li><a href="#">Role</a></li>
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
                                <a href="{{ route('admin.role.create') }}" class="btn btn-info"><i class="fa fa-plus-circle"></i> Add</a>
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
                                    <th style="width: 50%">Permissions</th>
                                    <th>Create</th>
                                    <th>Action</th>
                                </tr>
                                @if (isset($roles))
                                    @foreach($roles as $role)
                                        <tr>
                                            <td>{{ $role->id }}</td>
                                            <td>{{ $role->name }}</td>
                                            <td>{{ $role->guard_name }}</td>
                                            <td>
                                                @php
                                                    $permissions = $role->permissions()->pluck('name') ?? []
                                                @endphp
                                                @foreach($permissions as $permission)
                                                    <span class="label label-success">{{ $permission }}</span>
                                                @endforeach
{{--                                                {{ str_replace(array('[', ']','"'),'', $role->permissions()->pluck('name')) }}--}}
                                            </td>
                                            <td>{{ $role->created_at }}</td>
                                            <td>
                                                <a href="{{ route('admin.role.update', $role->id) }}" class="btn btn-xs btn-info"><i class="fa fa-pencil-square"></i> Edit</a>
                                                <a href="{{ route('admin.role.delete', $role->id) }}" class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i> Delete</a>
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
