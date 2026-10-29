<div class="modal-header">
    <h5 class="modal-title">Add New Slider</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="<?php echo site_url('slider/add-slider'); ?>" method="post" tts-form="true" name="add_slider"
    enctype="multipart/form-data">

    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label> Slider Image * </label>
                    <input class="form-control" type="file" name="slider_image" placeholder="Slider Image">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Status *</label>
                    <select class="form-select" name="status" placeholder="Status">
                        <option value="active" selected>Active</option>
                        <option value="inactive"> Inactive</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6" style="display: none;">
                <div class=" form-group">
                    <label>Category Type *</label>
                    <select class="form-select" name="image_category" placeholder="Slider Type">
                        <option value="Home-Slider" selected>Home-Slider</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Save</button>
    </div>
</form>