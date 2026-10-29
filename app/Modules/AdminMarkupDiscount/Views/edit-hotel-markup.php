<div class="modal-header">
   <h5>Edit <?php echo 'Hotel Markup '; ?></h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="<?php echo site_url('markup-discount/edit-hotel-markup/' . dev_encode($id)); ?>" method="post" tts-form="true" name="add_hotel_discount">
   <div class="modal-body">
      <div class="row align-items-center">
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Star Rating *</label>
               <?php $star_rating = explode(',', $details['star_rating']); ?>
               <select class="form-select select_search" name="star_rating[]" placeholder="Star Rating" multiple="multiple">
                  <option value="1" <?= (in_array(1, $star_rating) == true) ? 'selected' : '' ?>>1</option>
                  <option value="2" <?= (in_array(1, $star_rating) == true) ? 'selected' : '' ?>>2</option>
                  <option value="3" <?= (in_array(1, $star_rating) == true) ? 'selected' : '' ?>>3</option>
                  <option value="4" <?= (in_array(1, $star_rating) == true) ? 'selected' : '' ?>>4</option>
                  <option value="5" <?= (in_array(1, $star_rating) == true) ? 'selected' : '' ?>>5</option>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Region Type *</label>
               <?php $region_type = explode(',', $details['region_type']); ?>
               <select class="form-select select_search" name="region_type[]" placeholder="Region Type" multiple="multiple">
                  <option value="domestic" <?= (in_array("domestic", $region_type) == true) ? 'selected' : '' ?>>Domestic</option>
                  <option value="international" <?= (in_array("international", $region_type) == true) ? 'selected' : '' ?>>International</option>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Markup Type *</label>
               <select class="form-select" name="hotel_markup_type" placeholder="Markup Type">
                  <option value="per_night" <?= ($details['hotel_markup_type'] == "per_night") ? 'selected' : '' ?>>Per Room</option>
                  <option value="per_room" <?= ($details['hotel_markup_type'] == "per_room") ? 'selected' : '' ?>>Per Room</option>
               </select>
            </div>
         </div>
         <div class="col-md-4 mb-0">
            <div class="form-group form-mb-20">
               <label>Display Markup *</label>
               <select class="form-select" name="display_markup" placeholder="Display Markup">
                  <option value="in_tax" <?= ($details['display_markup'] == "in_tax") ? 'selected' : '' ?>>In Tax</option>
                  <option value="in_service_charge" <?= ($details['display_markup'] == "in_service_charge") ? 'selected' : '' ?>>In Service Charge</option>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Value *</label>
               <input class="form-control" type="text" name="value" value="<?= $details['value'] ?>" placeholder="Value">
            </div>
         </div>
         <div class="col-md-4 mb-0">
            <div class="form-group form-mb-20">
               <label>Status *</label>
               <select class="form-select" name="status" placeholder="Status">
                  <option value="active" <?= ($details['status'] == "active") ? 'selected' : '' ?>>Active</option>
                  <option value="inactive" <?= ($details['status'] == "inactive") ? 'selected' : '' ?>> Inactive</option>
               </select>
            </div>
         </div>
      </div>
   </div>
   <div class="modal-footer">
      <button class="badge badge-md badge-primary" type="submit" value="Save">Save</button>
   </div>
</form>