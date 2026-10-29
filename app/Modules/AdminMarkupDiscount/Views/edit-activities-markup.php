
 <div class="modal-header">
        <h5 class="modal-title">Edit <?php echo 'Activities Markup '; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

   <form action="<?php echo site_url('markup-discount/edit-admin-activities-markup/') . dev_encode($id); ?>" method="post" tts-form="true"
      name="edit_visa_markup">
      <div class="modal-body">
         <div class="row">
        
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Markup Type *</label>
                  <select class="form-control" name="markup_type" placeholder="Markup Type">
                     <option value="fixed" <?php if ($details['markup_type'] == "fixed") {
                        echo "selected";
                        
                        } ?>>Fixed</option>
                     <option value="percent" <?php if ($details['markup_type'] == "percent") {
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
                  <label>Display Markup *</label>
                  <select class="form-control" name="display_markup" placeholder="Display Markup">
                     <option value="in_tax" <?php if ($details['display_markup'] == "in_tax") {
                        echo "selected";
                        
                        } ?>>In Tax</option>
                     <option value="in_service_charge" <?php if ($details['display_markup'] == "in_service_charge") {
                        echo "selected";
                        
                        } ?> >In Service Charge</option>
                  </select>
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
         <div class="row">



               <div class="col-md-8">
                 <div class="form-group form-mb-20">
                     <label>Activities Destination *</label>
                     <input class="form-control <?php echo ($details['destination_name'] == 'ANY') ? 'tts-read-only' :''; ?>" type="text" value="<?php echo $details['destination_name'];?>" name="destination_name" tts-common-autocomplete="true" tts-method-name="<?php echo site_url("activities/get-destinations")?>" placeholder="Activities Destination"  <?php echo ($details['destination_name'] == 'ANY') ? 'readonly="readonly"' : ''; ?>>
                     <input type="hidden"  value="<?php echo $details['destination_id'];?>" name="destination_id" activities-destination-id="true">
                 </div>
               </div>
             
               <div class="col-md-4 align-self-end">
                  <div class="form-group form-mb-20">
                     <label>
                           <input type="checkbox" tts-commnon-any="true" resettag="activities-destination-id" value="ANY" class="Lead" <?php echo ($details['destination_name'] == 'ANY') ? 'checked' : ''; ?> > Any Destination Name
                     </label>
                  </div>
               </div> 

         </div>
      </div>
      <div class="modal-footer">
      <button class="btn btn-primary" type="submit">Save</button>
   </div>
   </form>
