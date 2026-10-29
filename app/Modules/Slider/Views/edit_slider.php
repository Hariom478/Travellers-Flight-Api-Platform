<div class="modal-header">
    <span class="close" onclick="ttsclosemodel('view_slider')">&times;</span>
    <h5>Edit Slider</h5>
</div>
<div class="vewmodelhed">

    <form action="<?php echo site_url('slider/edit-slider/' . dev_encode($id)); ?>" method="post" tts-form="true"
          name="edit_slider" enctype="multipart/form-data">

        <div class="modal-body">
            <div class="tts_row">

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Slider Text1</label>
                        <input class="form-control" type="text" name="slider_text1" placeholder="Slider Text1"
                               value="<?php echo $details['slider_text1']; ?>">
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Slider Text2</label>
                        <input class="form-control" type="text" name="slider_text2" placeholder="Slider Text2"
                               value="<?php echo $details['slider_text2']; ?>">
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Button Text</label>
                        <input class="form-control" type="text" name="url_button_text" placeholder="Button Text"
                               value="<?php echo $details['url_button_text']; ?>">
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label> Slider Image * </label>
                        <input class="form-control" type="file" name="slider_image" placeholder="Slider Image">
                    </div>
                </div>

                <div class="tts-col-12">
                    <div class="form-group">
                        <label>URL</label>
                        <input class="form-control" type="text" name="url" placeholder="URL"
                               value="<?php echo $details['url']; ?>">
                    </div>
                </div>


                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Status *</label>
                        <select class="form-control" name="status" placeholder="Blog Status">
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

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Slider Type *</label>
                        <select class="form-control" name="image_category" placeholder="Slider Type">
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
            <div class="tts_row">
                <div class="tts-col-12">
                    <input class="badge badge-md badge-primary" type="submit" value="Save">
                </div>
            </div>
        </div>
    </form>
</div>
