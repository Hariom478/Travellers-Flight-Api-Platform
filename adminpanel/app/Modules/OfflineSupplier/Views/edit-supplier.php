

<div class="modal-header">
        <h5 class="modal-title">Edit <?php echo ' '.$title;?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

    <form action="<?php echo site_url('offline-issue-supplier/edit-supplier/' . dev_encode($id)); ?>" method="post" onsubmit="return validateForm()" tts-form="true" name="edit_offline_supplier">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label> Supplier Name </label>
                        <input class="form-control" type="text" value="<?php echo $details['supplier_name'];?>" name="supplier_name" placeholder="Supplier Name">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label> Email Id  </label>
                        <input class="form-control" type="email" value="<?php echo $details['email'];?>" name="email" placeholder="Email Id">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Mobile Number</label>
                        <input class="form-control" type="text" name="mobile_no" value="<?php echo $details['mobile_no'];?>" placeholder="Mobile Number">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <select class="form-select" name="status">
                            <option value="">Select Status</option>
                            <option value="active" <?php if($details['status']=="active") { echo "selected"; }?>>Active</option>
                            <option value="inactive" <?php if($details['status']=="inactive") { echo "selected"; }?>>Inactive</option>
                        </select>
                    </div>
               </div>
            </div>
         
            <div class="row">
            <?php $activeService  =  get_active_whitelable_service(); ?>
            <?php foreach ($activeService as $service): $servicename  = strtolower($service);  ?>
                <div class="col-md-4 mb-2">
                    <label>
                        <input type="checkbox" name="<?php echo strtolower($service); ?>_service"  class="me-1" value="1"  <?php if(isset($details[$servicename.'_service']) && $details[$servicename.'_service']=='active'){ echo 'checked';}?>> <?php echo ucfirst($service); ?> Service
                    </label>
                </div>
            <?php endforeach; ?>
      
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save </button>
        </div>
    </form>


