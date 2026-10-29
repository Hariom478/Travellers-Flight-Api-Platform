
 <div class="modal-header">
        <h5 class="modal-title">Edit Package Price</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>


    <form action="<?php echo site_url("holiday/edit-holiday-price/").dev_encode($id) ?>" method="post" tts-form="true" name="edit_price"
          enctype="multipart/form-data">

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>Start Date *</label>
                        <input class="form-control" type="text" name="start_date" value="<?php echo $details['start_date'];?>"  data-searchbar-calendar-from="true" placeholder="Start Date" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>End Date *</label>
                        <input class="form-control" type="text" name="end_date" value="<?php echo $details['end_date'];?>" data-searchbar-calendar-to="true" placeholder="End Date" readonly>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Standard adult price *</label>
                        <input class="form-control" type="text" name="standard_adult_price" value="<?php echo $details['standard_adult_price'];?>" placeholder="Standard adult price">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Standard Child Price *</label>
                        <input class="form-control" type="text" name="standard_child_price" value="<?php echo $details['standard_child_price'];?>" placeholder="Standard Child Price">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Standard extra bed price *</label>
                        <input class="form-control" type="text" name="standard_extra_bad_price" value="<?php echo $details['standard_extra_bad_price'];?>" placeholder="Standard extra bed price">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Deluxe adult price *</label>
                        <input class="form-control" type="text" name="deluxe_adult_price"  value="<?php echo $details['deluxe_adult_price'];?>" placeholder="Deluxe adult price">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Deluxe child price *</label>
                        <input class="form-control" type="text" name="deluxe_child_price" value="<?php echo $details['deluxe_child_price'];?>" placeholder="Deluxe child price">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Deluxe extra bed child price *</label>
                        <input class="form-control" type="text" name="deluxe_extra_bad_price" value="<?php echo $details['deluxe_extra_bad_price'];?>" placeholder="Deluxe extra bed price">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Luxury adult price *</label>
                        <input class="form-control" type="text" name="luxury_adult_price" value="<?php echo $details['luxury_adult_price'];?>" placeholder="Luxury adult price">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Luxury child price *</label>
                        <input class="form-control" type="text" name="luxury_child_price" value="<?php echo $details['luxury_child_price'];?>" placeholder="Luxury child price">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>"Luxury extra child price *</label>
                        <input class="form-control" type="text" name="luxury_extra_bad_price" value="<?php echo $details['luxury_extra_bad_price'];?>" placeholder="Luxury extra bed price">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
             <button class="btn btn-primary" type="submit" >Save</button>
        </div>
    </form>


