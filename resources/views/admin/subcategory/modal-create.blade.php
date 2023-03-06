{{-- Modal add --}}
<div class="modal fade" id="modal-create-subcategory" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add new sub category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-create-subcategory">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="subcategory-name">Name</label>
                        <input type="text" class="form-control bg-white" id="subcategory-name">
                        <div class="invalid-feedback d-none" role="alert" id="alert-subcategory-name"></div>
                    </div>
                    <div class="form-group">
                        <label for="select-category-id">Category</label>
                        <select id="subcategory-categoryid" name="subcategory-categoryid" class="form-select mb-3 bg-white shadow-none"></select>
                        <div class="invalid-feedback d-none" role="alert" id="alert-subcategory-categoryid"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="store-subcategory">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>