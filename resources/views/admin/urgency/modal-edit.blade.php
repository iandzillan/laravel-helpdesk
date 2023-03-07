{{-- Modal edit --}}
<div class="modal fade" id="modal-edit-urgency" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit urgnecy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-edit-urgency">
                <div class="modal-body">
                    <input type="hidden" id="urgency-id">
                    <div class="form-group">
                        <label for="urgency-name-edit">Name</label>
                        <input type="text" class="form-control bg-white" id="urgency-name-edit">
                        <div class="invalid-feedback d-none" role="alert" id="alert-urgency-name-edit"></div>
                    </div>
                    <div class="form-group">
                        <label for="urgency-hours-edit">Duration</label>
                        <input type="number" class="form-control bg-white" id="urgency-hours-edit">
                        <div class="invalid-feedback d-none" role="alert" id="alert-urgency-hours-edit"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="update-urgency">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>