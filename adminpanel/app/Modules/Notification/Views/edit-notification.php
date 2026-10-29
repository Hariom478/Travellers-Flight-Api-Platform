<div class="modal-header">
    <h5 class="modal-title">Edit <?php echo ' '.$title;?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>


    <form action="<?php echo site_url('notification/edit-notification/' . dev_encode($id)); ?>" method="post" onsubmit="return validateForm()" tts-form="true" name="add_blogs" enctype="multipart/form-data">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label> Title </label>
                        <input class="form-control" type="text" value="<?php echo $details['title']?>" name="title" placeholder="title">
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label>Notification Type *</label>
                        <select class="form-select" name="type" >
                            <option value="important"  <?php if ($details['type'] == "important") {echo "selected";} ?>>Important</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label> Status *</label>
                        <select class="form-select" name="status">
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

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Description *</label>
                        <textarea class="form-control" type="textarea" name="description" rows="3" placeholder="Description"><?php echo $details['description']?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
           <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
