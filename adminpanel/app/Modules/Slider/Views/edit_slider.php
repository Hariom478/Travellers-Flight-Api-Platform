<div class="modal-header">
    <h5 class="modal-title">Edit Slider</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="<?php echo site_url('slider/edit-slider/' . dev_encode($id)); ?>" method="post" tts-form="true"
    name="edit_slider" enctype="multipart/form-data">

    <div class="modal-body">
        <div class="row">

            <div class="col-6">
                <div class="form-group">
                    <label> Slider Image * </label>
                    <input class="form-control" type="file" name="slider_image" placeholder="Slider Image">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label>Status *</label>
                    <select class="form-select" name="status" placeholder="Blog Status">
                        <option value="active" <?php if ($details['status'] == "active") {
                            echo "selected";
                        } ?>>Active
                        </option>
                        <option value="inactive" <?php if ($details['status'] == "inactive") {
                            echo "selected";
                        } ?>> Inactive
                        </option>
                    </select>
                </div>
            </div>

            <div class="col-6" style="display: none;">
                <div class="form-group">
                    <label>Category Type *</label>
                    <select class="form-select" name="image_category" placeholder="Slider Type">
                        <option value="Home-Slider" <?php if ($details['image_category'] == "Home-Slider") {
                            echo "selected";
                        } ?>>Home-Slider
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Save</button>
    </div>
</form>