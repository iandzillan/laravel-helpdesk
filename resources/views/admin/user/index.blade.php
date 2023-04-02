@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Users List</h4>
                </div>
                <div class="dropdown">
                    <a href="javascript:void(0)" class="btn btn-primary mb-2" role="button" id="btn-add-user">
                        <i class="fa-solid fa-folder-plus"></i>
                        Add User
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table display responsive nowrap" width=100%>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nik</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('admin.user.modal-create')
    @include('admin.user.modal-edit')
    @include('admin.user.script')
@endsection