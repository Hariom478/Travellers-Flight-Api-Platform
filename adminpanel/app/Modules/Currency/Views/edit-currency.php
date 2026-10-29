
<div class="modal-header">
        <h5 class="modal-title" >Edit <?php echo 'Currency ';?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>


    <form action="<?php echo site_url('currency/edit-currency/' . dev_encode($id)); ?>" method="post"
          onsubmit="return validateForm()" tts-form="true" name="add_blogs" enctype="multipart/form-data">

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label> Convertion Rate*  </label>
                        <input class="form-control" type="text" name="convertion_rate" value="<?php echo $details['convertion_rate']; ?>" placeholder="Convertion Rate">
                    </div>
                </div>


            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">save</button>
        </div>
    </form>


