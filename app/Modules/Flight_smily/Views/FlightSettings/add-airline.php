<div class="modal-header">
    <span class="close" onclick="ttsclosemodel(this)">&times;</span>
    <h5>Add <?php echo 'Airline';?></h5>
</div>
<div class="vewmodelhed">

    <form action="<?php echo site_url('flightsettings/add-airline'); ?>" method="post" tts-form="true" name="add_feedback" enctype="multipart/form-data">

        <div class="modal-body">
            <div class="tts_row">
                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Airline Code *  </label>
                        <input class="form-control" type="text" name="airline_code" placeholder="Airline Code">
                    </div>
                </div>
                <div class="tts-col-6">
                    <div class="form-group">
                        <label> Airline Name * </label>
                        <input class="form-control" type="text" name="airline_name" placeholder="Airline Name">
                    </div>
                </div>

              

                <div class="tts-col-6">
                    <div class="form-group">
                        <label> Airline Contact No </label>
                        <input class="form-control" type="text" name="airline_contact_no" placeholder="Airline Contact No">
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