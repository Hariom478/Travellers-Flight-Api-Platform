
<div class="modal-header">
        <h5 class="modal-title">Add <?php echo ' '.$title;?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>


    <form action="<?php echo site_url('offline-issue-supplier/add-supplier'); ?>" method="post" onsubmit="return validateForm()" tts-form="true" name="add_blogs" enctype="multipart/form-data">
  
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label> Supplier Name </label>
                        <input class="form-control" type="text" name="supplier_name" placeholder="Supplier Name">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label> Email Id </label>
                        <input class="form-control" type="email" name="email" placeholder="Email Id">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Mobile Number</label>
                        <input class="form-control" type="text" name="mobile_no" placeholder="Mobile Number">
                    </div>
                </div>

                <div class="col-md-4">
                     <div class="form-group form-mb-20">
                        <select class="form-select" name="status">
                           <option value="" selected="selected">Select Status</option>
                           <option value="active">Active</option>
                           <option value="inactive">Inactive</option>
                        </select>
                        
                     </div>
                </div>
            </div>
        
            <div class="row">
                <?php $activeService  =  get_active_whitelable_service(); ?>
                <?php foreach ($activeService as $service): ?>
                    <div class="col-md-4 mb-2">
                        <label>
                            <input type="checkbox" name="<?php echo strtolower($service); ?>_service"  class="me-1" value="1"> <?php echo ucfirst($service); ?> Service
                        </label>
                    </div>
                    <?php endforeach; ?>
             </div>
        </div>
        <div class="modal-footer">
             <button type="submit" class="btn btn-primary">Save </button>
        </div>
    </form>
