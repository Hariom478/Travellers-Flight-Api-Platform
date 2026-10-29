<div class="modal-header">
    <span class="close" onclick="ttsclosemodel('view_slider')">&times;</span>
    <h5>Add New Slider</h5>
</div>
<div class="vewmodelhed">

    <form action="<?php echo site_url('slider/add-slider'); ?>" method="post" tts-form="true" name="add_slider"
          enctype="multipart/form-data">

        <div class="modal-body">
            <div class="tts_row">
                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Slider Text1</label>
                        <input class="form-control" type="text" name="slider_text1" placeholder="Slider Text1">
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Slider Text2</label>
                        <input class="form-control" type="text" name="slider_text2" placeholder="Slider Text2">
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Button Text</label>
                        <input class="form-control" type="text" name="url_button_text" placeholder="Button Text">
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
                        <input class="form-control" type="text" name="url" placeholder="URL">
                    </div>
                </div>


                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Status *</label>
                        <select class="form-control" name="status" placeholder="Status">
                            <option value="active" selected>Active</option>
                            <option value="inactive"> Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Slider Type *</label>
                        <select class="form-control" name="image_category" placeholder="Slider Type">
                            <option value="Home-Slider" selected>Home-Slider</option>
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
    
