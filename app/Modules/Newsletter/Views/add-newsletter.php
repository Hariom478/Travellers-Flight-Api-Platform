<div class="modal-header">
    <span class="close" onclick="ttsclosemodel(this)">&times;</span>
    <h5>Add <?php echo ' '.$title;?></h5>
</div>
<div class="vewmodelhed">

    <form action="<?php echo site_url('newsletter/add-newsletter'); ?>" method="post" onsubmit="return validateForm()" tts-form="true" name="add_blogs" enctype="multipart/form-data">

        <div class="modal-body">
            <div class="tts_row">
                <div class="tts-col-6">
                    <div class="form-group">
                        <label> Name  </label>
                        <input class="form-control" type="text" name="name" placeholder="Name">
                    </div>
                </div>
                <div class="tts-col-6">
                    <div class="form-group">
                        <label> Email * </label>
                        <input class="form-control" type="email" name="email" placeholder="Email">
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