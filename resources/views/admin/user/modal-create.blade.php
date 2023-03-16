{{-- Modal add --}}
<div class="modal fade" id="modal-create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add new user</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-create-user">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="employee" class="form-label">Employee</label>
                            <select id="employee" name="employee" class="selectpicker form-control select2"></select>
                            <div class="invalid-feedback d-none" role="alert" id="alert-employee"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="role" class="form-label">Role</label>
                            <select id="role" name="role" class="selectpicker form-control select2"></select>
                            <div class="invalid-feedback d-none" role="alert" id="alert-role"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Email..." name="email" id="email"> 
                                <span class="input-group-text" id="domain-name">@example.com</span>
                            </div>
                            <div class="invalid-feedback d-none" role="alert" id="alert-email"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username...">
                            <div class="invalid-feedback d-none" role="alert" id="alert-username"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password...">
                            <div class="invalid-feedback d-none" role="alert" id="alert-password"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password-confirm" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password-confirm" name="password_confirmation" placeholder="Re-type password...">
                            <div class="invalid-feedback d-none" role="alert" id="alert-password-confirm"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="store-user">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>