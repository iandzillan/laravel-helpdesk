{{-- Modal edit --}}
<div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="category-id">

                <div class="form-group">
                    <label for="category-name">Name</label>
                    <input type="text" class="form-control" id="category-name-edit">
                    <div class="invalid-feedback d-none" role="alert" id="alert-category-name-edit"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update-category">Save</button>
            </div>
        </div>
    </div>
</div>