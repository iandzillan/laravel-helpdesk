{{-- Modal edit --}}
<div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit sub category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-edit-subcategory">
                <div class="modal-body">
                    <input type="hidden" id="subcategory-id">

                    <div class="form-group">
                        <label for="subcategory-name-edit">Name</label>
                        <input type="text" class="form-control" id="subcategory-name-edit">
                        <div class="invalid-feedback d-none" role="alert" id="alert-subcategory-name-edit"></div>
                    </div>

                    <div class="form-group">
                        <label for="select-category-id-edit">Category</label>
                        <select id="subcategory-categoryid-edit" name="category_id" class="form-select mb-3 select2-edit">
                        </select>
                        <div class="invalid-feedback d-none" role="alert" id="alert-subcategory-categoryid-edit"></div>
                    </div>

                    <div class="form-group">
                        <label for="technician-id-edit">Technician</label>
                        <select id="technician-id-edit" name="technician_id" class="form-select mb-3 select2-edit"></select>
                        <div class="invalid-feedback" role="alert" id="alert-technicianid-edit"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="update-subcategory">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>