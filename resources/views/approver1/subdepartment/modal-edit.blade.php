{{-- Modal edit --}}
<div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit sub department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-edit-subdept">
                <div class="modal-body">
                    <input type="hidden" id="subdept-id">
                    <div class="form-group">
                        <label for="subdept-name-edit">Name</label>
                        <input type="text" class="form-control" id="subdept-name-edit">
                        <div class="invalid-feedback d-none" role="alert" id="alert-subdept-name-edit"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="update-subdept">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>