<div class="modal-header">
    <h5 class="modal-title" >Edit <?php echo 'Visa Discount '; ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
    </button>
</div>

   <form action="<?php echo site_url('markup-discount/edit-admin-visa-discount/') . dev_encode($id); ?>" method="post" tts-form="true"
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
                  <label>Select Visa Country *</label>
                  <select class="form-control select_search" tts-method-name="visa/get-visa-list-select" name="visa_country_id" tts-call-select="true">
                     <option value="" selected>Select Visa Country</option>
                     <?php if ($country) {
                        foreach ($country as $country_code) { ?>
                     <option value="<?php echo $country_code['id'] ?>"
                        <?php if ( $country_code['id'] == $details['visa_country_id']) {  echo "Selected";  } ?>>
                        <?php echo $country_code['country_name']; ?>
                     </option>
                     <?php }
                        } ?>
                  </select>
               </div>
            </div>
             <div class="col-md-4">
                 <div class="form-group form-mb-20">
                     <label>Select Visa Type*</label>
                     <select class="form-control select_search" name="visa_type_id[]" tts-call-put-html="true" multiple="multiple">

                         <?php
                         if ($visa_list){

                             foreach ($visa_list as  $item){ ?>
                                 <option value=<?php echo $item['id']?> <?php if (in_array($item['id'], $details['visa_type_id'])) {echo "selected";} ?>><?php echo $item['visa_title']?></option>
                             <?php }
                         }

                         ?>
                     </select>
                 </div>
             </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Discount Type *</label>
                  <select class="form-control" name="discount_type" placeholder="Discount Type">
                     <option value="fixed" <?php if ($details['discount_type'] == "fixed") {
                        echo "selected";
                        
                        } ?>>Fixed</option>
                     <option value="percent" <?php if ($details['discount_type'] == "percent") {
                        echo "selected";
                        
                        } ?> >Percent</option>
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
         </div>
      </div>
      <div class="modal-footer">
         <button class="btn btn-primary" type="submit">Save</button>
      </div>
   </form>
