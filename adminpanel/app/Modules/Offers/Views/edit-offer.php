
<div class="modal-header">
        <h5 class="modal-title" >Edit <?php echo ' '.$title;?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>


    <form action="<?php echo site_url('offers/edit-offer/' . dev_encode($id)); ?>" method="post"
          onsubmit="return validateForm()" tts-form="true" name="add_blogs" enctype="multipart/form-data">

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label> Title*  </label>
                        <input class="form-control" type="text" name="title" value="<?php echo $details['title']; ?>" placeholder="Title">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label> URL  </label>
                        <input class="form-control" type="text" name="url" placeholder="URL" value="<?php echo $details['url']; ?>" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                    <?php $active_service=get_active_whitelable_service();?>
                        <label>Services *</label>
                        <select class="form-select" name="service" placeholder="Services">
                            <option value="">Select Service</option>
                           <?php if($active_service) 
                           {
                            foreach($active_service as $service) 
                                { ?>
                                    <option value="<?php echo strtolower($service); ?>" <?php if ($details['service']==strtolower($service)) { echo "selected"; } ?> ><?php echo $service; ?></option>
                               <?php }
                           }
                           ?>

                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>Offer Image * </label>
                        <input class="form-control" type="file" name="image" placeholder="Offer Image">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group form-mb-20">
                        <label> Description * </label>
                        <input class="form-control" type="text" name="description" value="<?php echo $details['description']; ?>" placeholder="Description">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">save</button>
        </div>
    </form>


