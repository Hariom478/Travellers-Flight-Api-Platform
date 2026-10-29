<div class="modal-header">
   <h5>Add <?php echo 'Hotel Markup '; ?></h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="<?php echo site_url('markup-discount/add-hotel-markup'); ?>" method="post" tts-form="true" name="add_hotel_discount">
   <div class="modal-body">
      <div class="row align-items-center gy-3">
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Star Rating *</label>
               <select class="form-select select_search" name="star_rating[]" placeholder="Star Rating" multiple="multiple">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Region Type *</label>
               <select class="form-select select_search" name="region_type[]" placeholder="Region Type" multiple="multiple">
                  <option value="domestic" selected>Domestic</option>
                  <option value="international">International</option>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Markup Type *</label>
               <select class="form-select" name="hotel_markup_type" placeholder="Markup Type">
                  <option value="per_night" selected>Per Room</option>
                  <option value="per_room">Per Room</option>
               </select>
            </div>
         </div>
         <div class="col-md-4 mb-0">
            <div class="form-group form-mb-20">
               <label>Display Markup *</label>
               <select class="form-select" name="display_markup" placeholder="Display Markup">
                  <option value="in_tax" selected>In Tax</option>
                  <option value="in_service_charge">In Service Charge</option>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Value *</label>
               <input class="form-control" type="text" name="value" placeholder="Value">
            </div>
         </div>
         <div class="col-md-4 mb-0">
            <div class="form-group form-mb-20">
               <label>Status *</label>
               <select class="form-select" name="status" placeholder="Status">
                  <option value="active" selected>Active</option>
                  <option value="inactive"> Inactive</option>
               </select>
            </div>
         </div>
      </div>
   </div>
   <div class="modal-footer">
      <button class="btn btn-outline-primary" type="submit" value="Save">Save</button>
   </div>
</form>