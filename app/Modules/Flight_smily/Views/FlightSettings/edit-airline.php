<div class="modal-header">
    <span class="close" onclick="ttsclosemodel(this)">&times;</span>
    <h5>Edit <?php echo 'Airline';?></h5>
</div>
<div class="vewmodelhed">

    <form action="<?php echo site_url('flightsettings/edit-airline/' . dev_encode($id)); ?>" method="post"
          tts-form="true" name="edit_airport" enctype="multipart/form-data">
        <div class="modal-body">
            <div class="tts_row">
                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Airline Code *  </label>
                        <input class="form-control" type="text" name="airline_code" value="<?php echo $details['airline_code']?>" placeholder="Airline Code" >
                    </div>
                </div>
                <div class="tts-col-6">
                    <div class="form-group">
                        <label> Airline Name * </label>
                        <input class="form-control" type="text" name="airline_name"  value="<?php echo $details['airline_name']?>" placeholder="Airline Name">
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label> Airline Contact No </label>
                        <input class="form-control" type="text" name="airline_contact_no" value="<?php echo $details['airline_contact_no']?>" placeholder="Airline Contact No">
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