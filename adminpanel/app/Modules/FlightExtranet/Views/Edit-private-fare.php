<div class="content">
   <div class="page-content">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4 mb-3 mb-lg-0">
                  <h5 class="m-0">Edit Inventory</h5>
               </div>
               <div class="col-md-8 text-md-right">
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="card">
               <div class="card-body">
                  <div class="row">
                     <div class="col-md-12">
                     <form name="web-partner" tts-form='true'
                  action="<?php  echo site_url('private-fare/edit-private-fare/') . dev_encode($id); ?>"
                  method="POST" id="web-partner">
                  <div class="row">
                     <div class="col-md-3">
                        <div class="form-group form-mb-20">
                           <label>Inventory Name</label>
                           <input class="form-control" type="text" name="inventory_name" value="<?php echo $details['inventory_name']?>"
                              placeholder="Inventory Name">
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="form-group form-mb-20">
                           <label>Disable Before departure (hr)</label>
                           <input class="form-control" type="text" name="disable_before_departure" value="<?php echo $details['disable_before_departure']?>"
                              placeholder="Disable Before departure (hr)">
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="form-group form-mb-20">
                           <label>Trip Type </label>
                           <select class="form-control trip_type" name="trip_type" placeholder="Trip Type" privatefare-trip-type-select =  "true">
                              <option value="domestic" <?php if ($details['trip_type'] == "domestic") {
                                 echo "selected";
                                 
                                 } ?>>Domestic</option>
                              <option value="international" <?php if ($details['trip_type'] == "international") {
                                 echo "selected";
                                 
                                 } ?>>International</option>
                           </select>
                        </div>
                     </div>
                     <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>Journey Type *</label>
                        <select class="form-control journey_type" name="journey_type" placeholder="Journey Type" privatefare-journey-type-select =  "true">
                           <option value="oneway" <?php if ($details['journey_type'] == "domestic") {
                                 echo "selected";
                                 
                                 } ?>>Oneway</option>
                              <option value="roundtrip" <?php if ($details['trip_type'] == "international"&&     $details['journey_type'] == "roundtrip") {
                                 echo "selected";
                                 
                                 } ?>>Roundtrip</option>
                        </select>
                     </div>
                  </div>
                   
                  <input type="hidden" name="onward_stops" id="tts-segment-counter" value="<?php echo $onward_stops ?>">
                  <input type="hidden" name="return_stops" id="tts-segment-return-counter" value="<?php echo $return_stops ?>">
                  <div class="col-12">
                     <?php echo $segment ?>
                    
                  </div>
                  <div class="col-12 form__buttons text-md-right">
                     <button type="submit" class="btn btn-primary">Save & Continue</button>
                  </div>
               </form>
                      </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>