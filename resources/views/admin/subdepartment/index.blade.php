@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Sub Departments</h4>
                </div>
                <a href="javascript:void(0)" class="btn btn-primary mb-2" id="btn-create-category">
                    <i class="fa-solid fa-folder-plus"></i>
                    Add Sub Department
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table" data-toggle="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sub_departments as $sub_department)
                                <tr id="index_{{$sub_department->id}}">
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$sub_department->name}}</td>
                                    <td>{{$sub_department->department->name}}</td>
                                    <td>
                                        <a href="javascript:void(0)" id="btn-edit-subdepartment" data-id="{{$sub_department->id}}" class="btn btn-primary btn-sm" title="Edit this sub department">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="javascript:void(0)" id="btn-delete-subdepartment" data-id="{{$sub_department->id}}" class="btn btn-danger btn-sm" title="Delete this sub department">
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