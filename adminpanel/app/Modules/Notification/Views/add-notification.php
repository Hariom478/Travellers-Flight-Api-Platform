<div class="modal-header">
     <h5 class="modal-title">Add <?php echo ' '.$title;?></h5>
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
    <form action="<?php echo site_url('notification/add-notification'); ?>" method="post" onsubmit="return validateForm()" tts-form="true" name="add_feedback" enctype="multipart/form-data">

        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label> Title *  </label>
                        <input class="form-control" type="text" name="title" placeholder="Title">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Notification Type *</label>
                        <select class="form-select" name="type">
                            <option value="important">Important</option>
                        </select>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status *</label>
                        <select class="form-select" name="status">
                            <option value="active"> Active</option>
                            <option value="inactive" > Inactive </option>
                        </select>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label> Description *</label>
                        <textarea class="form-control " type="textarea" name="description" rows="3" placeholder=" Description"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
           <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
