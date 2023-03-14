{{-- Modal user request --}}
<div class="modal fade" id="modal-user-request" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">User account request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-user-request">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nik">NIK</label>
                        <input type="text" class="form-control bg-white" id="nik" name="nik" readonly>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control bg-white" id="name" name="name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="email" id="email"> 
                            <span class="input-group-text" id="basic-addon2">@example.com</span>
                        </div>
                        <div class="invalid-feedback d-none" role="alert" id="alert-email"></div>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control bg-white" id="username" name="username">
                        <div class="invalid-feedback d-none" role="alert" id="alert-username"></div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control bg-white" id="password" name="password">
                        <div class="invalid-feedback d-none" role="alert" id="alert-password"></div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" class="form-control bg-white" id="confirm" name="password_confirmation">
                        <div class="invalid-feedback d-none" role="alert" id="alert-confirm"></div>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="category" class="form-select mb-3 bg-white shadow-none select-modal">
                            <option selected>-- Choose --</option>
                            <option value="User">User</option>
                            <option value="Technician">Technician</option>
                        </select>
                        <div class="invalid-feedback d-none" role="alert" id="alert-role"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="request">Send Request</button>
                </div>
            </form>
        </div>
    </div>
</div>