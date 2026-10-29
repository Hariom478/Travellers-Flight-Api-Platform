<div class="modal-header">
    <h5 class="modal-title">Edit <?php echo 'Cruise Discount '; ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

   <form action="<?php echo site_url('markup-discount/edit-admin-cruise-discount/') . dev_encode($id); ?>" method="post" tts-form="true"
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
                  <label>Select Cruise Departure Port *</label>
                  <select class="form-control select_search" name="departure_port_id">
                     <option value=""  >Select Departure Port</option>
                     <option value="ANY" <?php if ($details['departure_port_id']=='ANY') {  echo "Selected";  } ?> >ANY Port</option>
                     <?php if ($cruise_port) {
                        foreach ($cruise_port as $data) { ?>
                     <option value="<?php echo $data['id'] ?>" <?php if ( $data['id'] == $details['departure_port_id']) {  echo "Selected";  } ?>>
                        <?php echo ucfirst($data['port_name']); ?>
                     </option>
                     <?php }
                        } ?>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Select Cruise Line *</label>
                  <select class="form-control select_search" tts-method-name="cruise/get-cruise-ship-id-select" name="cruise_line_id" tts-call-select="true">
                     <option value="" >Select Cruise Line</option>
                     <option value="ANY" <?php if ($details['cruise_line_id']=='ANY') {  echo "Selected";  } ?>>ANY Cruise Line</option>
                     <?php if ($cruise_line) {
                        foreach ($cruise_line as $data) { ?>
                     <option value="<?php echo $data['id'] ?>" <?php if ( $data['id'] == $details['cruise_line_id']) {  echo "Selected";  } ?>>
                        <?php echo $data['cruise_line_name']; ?>
                     </option>
                     <?php }
                        } ?>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Select Cruise Ship *</label>
                  <select class="form-control select_search" name="cruise_ship_id" tts-call-put-html="true" tts-method-name="cruise/get-cruise-cabin-id-select" tts-call-select="true">
                     <option value="" >Select Cruise Ship</option>
                     <option value="ANY" <?php if ($details['cruise_ship_id']=='ANY') {  echo "Selected";  } ?>>ANY Cruise Ship</option>
                     <?php
                        if ($cruise_ship){
                        
                            foreach ($cruise_ship as  $item){ ?>
                     <option value=<?php echo $item['id']?> <?php if ($details['cruise_ship_id'] == $item['id']) {echo "selected";} ?>><?php echo $item['ship_name']?></option>
                     <?php }
                        }
                        
                        ?>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Select Cruise Cabin *</label>
                  <select class="form-control select_search" name="cabin_id" tts-call-cruise-cabin-put-html="true">
                     <option value="" >Select Cruise Cabin</option>
                     <option value="ANY" <?php if ($details['cabin_id']=='ANY') {  echo "Selected";  } ?>>ANY Cruise Cabin</option>
                     <?php
                        if ($cruise_cabin){
                        
                            foreach ($cruise_cabin as  $item){ ?>
                     <option value=<?php echo $item['id']?> <?php if ($details['cabin_id'] == $item['id']) {echo "selected";} ?>><?php echo $item['cabin_name']?></option>
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
