@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Users</h4>
                </div>
                <a href="javascript:void(0)" class="btn btn-primary mb-2" id="btn-create-category">
                    <i class="fa-solid fa-folder-plus"></i>
                    Add User
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table" data-toggle="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Department</th>
                                <th>Sub Department</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr id="index_{{$user->id}}">
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$user->employee->name}}</td>
                                    <td>{{$user->employee->position->name}}</td>
                                    <td>{{$user->employee->position->subDepartment->name}}</td>
                                    <td>{{$user->employee->position->subDepartment->department->name}}</td>
                                    <td>{{$user->username}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>
                                        @if ($user->role == 'Admin')
                                            <div class="badge bg-primary">{{$user->role}}</div>
                                        @endif
                                        @if ($user->role == 'Approver')
                                            <div class="badge bg-success">{{$user->role}}</div>
                                        @endif
                                        @if ($user->role == 'User')
                                            <div class="badge bg-info">{{$user->role}}</div>
                                        @endif
                                        @if ($user->role == 'Technician')
                                            <div class="badge bg-warning">{{$user->role}}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" id="btn-edit-subdepartment" data-id="{{$user->id}}" class="btn btn-primary btn-sm" title="Edit this sub department">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="javascript:void(0)" id="btn-delete-subdepartment" data-id="{{$user->id}}" class="btn btn-danger btn-sm" title="Delete this sub department">
                                            <i class="fa-solid fa-eraser"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection