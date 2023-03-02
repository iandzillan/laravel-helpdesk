@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Departments</h4>
                </div>
                <a href="javascript:void(0)" class="btn btn-primary mb-2" id="btn-create-category">
                    <i class="fa-solid fa-folder-plus"></i>
                    Add Department
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table" data-toggle="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $department)
                                <tr id="index_{{$department->id}}">
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$department->name}}</td>
                                    <td>
                                        <a href="javascript:void(0)" id="btn-edit-department" data-id="{{$department->id}}" class="btn btn-primary btn-sm" title="Edit this department">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="javascript:void(0)" id="btn-delete-department" data-id="{{$department->id}}" class="btn btn-danger btn-sm" title="Delete this department">
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