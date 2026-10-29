
<div class="modal-header">
        <h5 class="modal-title">Edit <?php echo ' '.$title;?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>


    <form action="<?php echo site_url('newsletter/edit-newsletter/' . dev_encode($id)); ?>" method="post"
          onsubmit="return validateForm()" tts-form="true" name="add_blogs" enctype="multipart/form-data">

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label> Name </label>
                        <input class="form-control" type="text" value="<?php echo $details['name']?>" name="name" placeholder="name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>Email * </label>
                        <input class="form-control" type="email" value="<?php echo $details['email']?>" name="email" placeholder="Email">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>


