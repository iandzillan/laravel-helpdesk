@extends('layouts.app')

@section('content')
    <form id="form-create-employee">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="form-group col-md-12">
                                <div class="profile-img-edit position relative mb-3">
                                    <img src="{{ asset('assets/images/avatars/01.png') }}" alt="profile-pic" class="theme-color-default-img profile-pic rounded avatar-100">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <input type="file" class="form-control-sm" id="inputGroupFile01">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title text-bold">New Employee Information</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="new-user info">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="employee-nik" class="form-label">NIK</label>
                                    <input type="text" class="form-control" id="employee-nik" placeholder="Employee's nik">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="employee-name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="employee-name" placeholder="Employee's name">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="employee-dept" class="form-label">Department</label>
                                    <select id="employee-dept" class="form-select"></select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="employee-subdept" class="form-label">Sub Department</label>
                                    <select id="employee-subdept" class="form-select"></select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="employee-position" class="form-label">Position</label>
                                    <select id="employee-position" class="form-select"></select>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" id="store-employee">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection