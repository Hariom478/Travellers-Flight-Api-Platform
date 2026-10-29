

 <div class="modal-header">
        <h5 class="modal-title">Edit Holiday Inclusion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>


    <form action="<?php echo site_url('holiday/edit-inclusion/').dev_encode($id); ?>" method="post" tts-form="true" name="edit_holiday_theme"
          enctype="multipart/form-data">

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>Inclusion Title *</label>
                        <input class="form-control" type="text" name="inclusion_title" value="<?php echo $details['inclusion_title']?>" placeholder="Inclusion Title" >
                    </div>
                </div>



                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>Status *</label>
                        <select class="form-select" name="status" placeholder="Status">
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

            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit" value="Save">Save</button>
           
        </div>
    </form>


