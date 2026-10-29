<div class="modal-header">
    <span class="close" onclick="ttsclosemodel(this)">&times;</span>
    <h5>Edit <?php echo 'Airport';?></h5>
</div>
<div class="vewmodelhed">


        <form action="<?php echo site_url('flightsettings/edit-airports/' . dev_encode($id)); ?>" method="post"
               tts-form="true" name="edit_airport" enctype="multipart/form-data">
        <div class="modal-body">
            <div class="tts_row">
                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Airport Code *  </label>
                        <input class="form-control" type="text" name="code"
                               placeholder="Airport Code" value="<?php echo $details['code']?>" >
                    </div>
                </div>
                <div class="tts-col-6">
                    <div class="form-group">
                        <label> Name * </label>
                        <input class="form-control" type="text" name="name" value="<?php echo $details['name']?>" placeholder="Name">
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label> City Name * </label>
                        <input class="form-control" type="text" name="city_name" value="<?php echo $details['city_name']?>" placeholder="City Name">
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label> City Code * </label>
                        <input class="form-control" type="text" name="city_code" value="<?php echo $details['city_code']?>" placeholder="City Code">
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label> Country Name * </label>
                        <input class="form-control" type="text" name="country_name" value="<?php echo $details['country_name']?>" placeholder="Country Name">
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label> Country Code * </label>
                        <input class="form-control" type="text" name="country_code" value="<?php echo $details['country_code']?>" placeholder="Country Code">
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