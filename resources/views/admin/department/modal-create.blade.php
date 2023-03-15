{{-- Modal add --}}
<div class="modal fade" id="modal-create-department" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add new department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-create-department">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="department-name">Name</label>
                        <input type="text" class="form-control" id="department-name">
                        <div class="invalid-feedback d-none" role="alert" id="alert-department-name"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="store-department">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>