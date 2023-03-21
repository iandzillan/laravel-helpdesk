{{-- Modal edit --}}
<div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit user</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-edit-user">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" id="id" name="id">
                        <div class="form-group col-md-6">
                            <label for="user-edit" class="form-label">user</label>
                            <input type="text" class="form-control" id="user-edit" name="user" readonly>
                            <div class="invalid-feedback d-none" role="alert" id="alert-user-edit"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="role-edit" class="form-label">Role</label>
                            <select id="role-edit" name="role" class="selectpicker form-control select2-edit"></select>
                            <div class="invalid-feedback d-none" role="alert" id="alert-role-edit"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email-edit" class="form-label">Email</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="email" id="email-edit"> 
                                <span class="input-group-text" id="domain-name-edit">@example.com</span>
                            </div>
                            <div class="invalid-feedback d-none" role="alert" id="alert-email-edit"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="username-edit" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username-edit" name="username">
                            <div class="invalid-feedback d-none" role="alert" id="alert-username-edit"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password-edit" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password-edit" name="password">
                            <div class="invalid-feedback d-none" role="alert" id="alert-password-edit"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password-confirm-edit" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password-confirm-edit" name="password_confirmation">
                            <div class="invalid-feedback d-none" role="alert" id="alert-password-confirm-edit"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="update-user">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>