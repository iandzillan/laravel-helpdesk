{{-- Modal add --}}
<div class="modal fade" id="modal-create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add new category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-create-category">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="category-name">Name</label>
                        <input type="text" class="form-control" id="category-name">
                        <div class="invalid-feedback d-none" role="alert" id="alert-category-name"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="store-category">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>