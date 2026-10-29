
<div class="modal-header">
                <h5 class="modal-title">Edit <?php echo ' ' . ' Flight API Management'; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
   <form action="<?php echo site_url('supplier-management/edit-flight-api-mgt/' . dev_encode($id)); ?>" method="post"
      onsubmit="return validateForm()" tts-form="true" name="add_supplier" enctype="multipart/form-data">
      <div class="modal-body">
         <div class="row">
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Suppliers*</label>
                  <select class="form-control" name="api_supplier_id">
                     <option value="">Select Suppler</option>
                     <?php if ($supplier_list) {
                        foreach ($supplier_list as $list) { ?>
                     <option value="<?php echo $list['id'] ?>" <?php if ($details['api_supplier_id'] == $list['id']) {
                        echo "selected";
                        } ?> ><?php echo $list['supplier_name'] ?></option>
                     <?php }
                        } ?>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Air Type *</label>
                  <select class="form-control" name="air_type">
                     <option value="All" <?php if ($details['air_type'] == "All") {
                        echo "selected";
                        } ?> > All
                     </option>
                     <option value="Domestic" <?php if ($details['air_type'] == "Domestic") {
                        echo "selected";
                        } ?> > Domestic
                     </option>
                     <option value="International" <?php if ($details['air_type'] == "International") {
                        echo "selected";
                        } ?> > International
                     </option>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Search Type *</label>
                  <select class="form-control" name="search_type">
                     <option value="All" <?php if ($details['search_type'] == "All") {
                        echo "selected";
                        } ?>> All
                     </option>
                     <option value="Oneway" <?php if ($details['search_type'] == "Oneway") {
                        echo "selected";
                        } ?>> Oneway
                     </option>
                     <option value="Roundtrip" <?php if ($details['search_type'] == "Roundtrip") {
                        echo "selected";
                        } ?>> Roundtrip
                     </option>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label> Allowed Airline </label>
                  <input class="form-control" tts-get-airline-multiple="true" type="text"
                     value="<?php echo $details['allowed_airline']; ?>" name="allowed_airline"
                     placeholder="Allowed Airline">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label> Excluded Airline </label>
                  <input class="form-control" type="text" tts-get-airline-multiple="true"
                     value="<?php echo $details['excluded_airline']; ?>" name="excluded_airline"
                     placeholder="Excluded Airline">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Fare Type</label>
                  <select class="form-control select_search" name="fare_type[]" multiple="multiple">
                     <option value="">Select Fare Type</option>
                     <?php
                        $get_fare_type = get_fare_type();
                        if ($get_fare_type) {
                            foreach ($get_fare_type as $key => $list) { ?>
                     <option value="<?php echo $key ?>" <?php if (in_array($key, $details['fare_type'])) {
                        echo "selected";
                        } ?> ><?php echo $key ?></option>
                     <?php }
                        } ?>
                  </select>
               </div>
            </div>
         </div>
      </div>
      <div class="modal-footer">
         <button class="btn btn-primary" type="submit" >Save</button>
      </div>
   </form>
