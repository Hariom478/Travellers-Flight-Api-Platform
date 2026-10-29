
<div class="modal-header">
   <h5 class="modal-title" >Edit <?php echo 'Hotel Markup '; ?></h5>
   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
   <span aria-hidden="true">&times;</span>
   </button>
</div>
<form action="<?php echo site_url('markup-discount/edit-admin-hotel-markup/') . dev_encode($id); ?>" method="post" tts-form="true"
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
               <label>Supplier *</label>
               <select class="form-control select_search" name="supplier[]"  multiple="multiple">
                  <?php if ($ApiSupplierModel){
                     foreach ($ApiSupplierModel as $list){ ?>
                  <option value="<?php echo $list['supplier_name']?>" <?php if (in_array($list['supplier_name'], $details['supplier'])) {
                     echo "selected";
                     
                     } ?> ><?php echo $list['supplier_name']?></option>
                  <?php }
                     }?>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Region Type *</label>
               <select class="form-control select_search" name="region_type[]"  multiple="multiple">
                  <option value="domestic" <?php if (in_array("domestic", $details['region_type'])) {echo "selected";} ?> >Domestic</option>
                  <option value="international" <?php if (in_array("international", $details['region_type'])) {echo "selected";} ?>>International</option>
               </select>
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
               <label>Hotel Markup Type *</label>
               <select class="form-control" name="hotel_markup_type" placeholder="Hotel Markup Type">
                  <option value="per_night" <?php if ($details['hotel_markup_type'] == "per_night") {
                     echo "selected";
                     
                     } ?>>Per Night</option>
                  <option value="per_room" <?php if ($details['hotel_markup_type'] == "per_room") {
                     echo "selected";
                     
                     } ?>>Per Room</option>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Star Rating *</label>
               <select class="form-control select_search" name="star_rating[]" placeholder="Star Rating" multiple="multiple">
                  <?php  for($rating=1;$rating<=5;$rating++){ ?>
                     <option value="<?php echo  $rating;?>" <?php  echo  in_array($rating,$details['star_rating'])?"selected":"";?>><?php echo  $rating;?> Star</option>
                 <?php   } ?>
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