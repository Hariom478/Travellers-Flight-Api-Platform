
<div class="modal-header">
        <h5 class="modal-title">Add Holiday Inclusion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>


    <form action="<?php echo site_url('holiday/add-inclusion'); ?>" method="post" tts-form="true" name="add_inclusion"
          enctype="multipart/form-data">

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>Inclusion Title *</label>
                        <input class="form-control" type="text" name="inclusion_title" placeholder="Inclusion Title">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>Status *</label>
                        <select class="form-select" name="status" placeholder="Status">
                            <option value="active" selected>Active</option>
                            <option value="inactive"> Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit" >save</button>
        </div>
    </form>


