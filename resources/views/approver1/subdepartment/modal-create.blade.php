{{-- Modal add --}}
<div class="modal fade" id="modal-create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add new sub department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-create-subdept">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="subdept-name">Name</label>
                        <input type="text" class="form-control shadow-none" id="subdept-name">
                        <div class="invalid-feedback d-none" role="alert" id="alert-subdept-name"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="store-subdept">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>