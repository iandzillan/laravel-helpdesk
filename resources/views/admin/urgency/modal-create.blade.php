{{-- Modal add --}}
<div class="modal fade" id="modal-create-urgency" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add new urgnecy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-create-urgency">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="urgency-name">Name</label>
                        <input type="text" class="form-control bg-white" id="urgency-name">
                        <div class="invalid-feedback d-none" role="alert" id="alert-urgency-name"></div>
                    </div>
                    <div class="form-group">
                        <label for="urgency-hours">Duration</label>
                        <input type="number" class="form-control bg-white" id="urgency-hours">
                        <div class="invalid-feedback d-none" role="alert" id="alert-urgency-hours"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="store-urgency">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>