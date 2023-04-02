{{-- Modal add --}}
<div class="modal fade" id="modal-create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add new sub category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-create-subcategory">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name">
                        <div class="invalid-feedback" role="alert" id="alert-name"></div>
                    </div>
                    <div class="form-group">
                        <label for="category-id">Category</label>
                        <select id="category-id" name="category_id" class="form-select mb-3 select2"></select>
                        <div class="invalid-feedback" role="alert" id="alert-categoryid"></div>
                    </div>
                    <div class="form-group">
                        <label for="technician-id">Technician</label>
                        <select id="technician-id" name="technician_id" class="form-select mb-3 select2"></select>
                        <div class="invalid-feedback" role="alert" id="alert-technicianid"></div>
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