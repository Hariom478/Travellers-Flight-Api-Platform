
 <div class="modal-header">
        <h5 class="modal-title">Edit <?php echo 'Holiday Discount '; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
</div>

   <form action="<?php echo site_url('markup-discount/edit-admin-holiday-discount/') . dev_encode($id); ?>" method="post" tts-form="true"
      name="edit_visa_markup">
      <div class="modal-body">
         <div class="row">
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Web Partner Class *</label>
                  <select class="form-control select_search" name="web_partner_class_id[]" multiple="multiple"
                     placeholder="Web Partner Class">
                     <?php if ($web_partner_class) {
                        foreach ($web_partner_class as $class) { ?>
                     <option value="<?php echo $class['id'] ?>" <?php if (in_array($class['id'], $details['web_partner_class_id'])) {echo "selected";} ?>   ><?php echo ucfirst($class['class_name']) ?></option>
                     <?php }
                        } ?>
                  </select>
               </div>
            </div>
   
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Value *</label>
                  <input class="form-control" type="text" value="<?php echo $details['value'];?>" name="value" placeholder="Value">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Max Limit </label>
                  <input class="form-control" type="text" value="<?php echo $details['max_limit'];?>" name="max_limit" placeholder="Max Limit">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Extra Discount </label>
                  <input class="form-control" type="text" value="<?php echo $details['extra_discount'];?>" name="extra_discount" placeholder="Extra Discount">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Status *</label>
                  <select class="form-control" name="status" placeholder="Status">
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


             <div class="col-md-4">
                 <div class="form-group form-mb-20">
                     <label>Holiday Destination *</label>
                     <input class="form-control" type="text" value="<?php echo $details['destination_name'];?>" name="destination_name" holiday-destination="true" tts-method-name="<?php echo site_url("holiday/get-destinations")?>" placeholder="Holiday Destination">
                     <input type="hidden"  value="<?php echo $details['destination_id'];?>" name="destination_id" tts-destination-id="true">
                 </div>
             </div>
             <div class="col-md-4">
                 <div class="form-group form-mb-20">
                     <label>Holiday Theme *</label>
                     <input class="form-control" type="text" value="<?php echo $details['theme_name'];?>" name="theme_name" placeholder="Holiday Destination" holiday-theme="true" tts-method-name="<?php echo site_url("holiday/get-themes")?>">
                     <input type="hidden" name="theme_id" tts-theme-id="true" value="<?php echo $details['theme_id'];?>">
                 </div>
             </div>

         </div>
      </div>
      <div class="modal-footer">
         <button class="btn btn-primary" type="submit" >Save</button>
      </div>
   </form>
