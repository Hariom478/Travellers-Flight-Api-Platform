<div class="modal-header">
        <h5 class="modal-title">Add  PNR (<?php echo ' '.$title;?>)</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
   <form action="<?php echo site_url('offers/add-offer'); ?>" method="post" onsubmit="return validateForm()" tts-form="true" name="add_blogs" enctype="multipart/form-data">
      <div class="modal-body">
         <div class="row">
            <div class="col-md-2">
               <div class="form-group form-mb-20">
                  <label> Departure Date*  </label>
                  <input class="form-control" type="text" name="departure_date" placeholder="Departure Date">
               </div>
            </div>
            <?php  if($details['journey_type']=="roundtrip") { ?>
            <div class="col-md-2">
               <div class="form-group form-mb-20">
                  <label> Return Date*  </label>
                  <input class="form-control" type="text" name="return_date" placeholder="Return Date">
               </div>
            </div>
            <?php } ?>
            <div class="col-md-2">
               <div class="form-group form-mb-20">
                  <label> PNR  </label>
                  <input class="form-control" type="text" name="pnr" placeholder="PNR">
               </div>
            </div>
            <?php  if($details['journey_type']=="roundtrip") { ?>
            <div class="col-md-2">
               <div class="form-group form-mb-20">
                  <label> Return PNR  </label>
                  <input class="form-control" type="text" name="return_pnr" placeholder="Return PNR">
               </div>
            </div>
            <?php  } ?>
            <div class="col-md-2">
               <div class="form-group form-mb-20">
                  <label>Total Purchased Seats* </label>
                  <input class="form-control" type="text" name="total_purchased_seat" placeholder="Total Purchased Seats">
               </div>
            </div>
            <div class="col-md-2">
               <div class="form-group form-mb-20">
                  <label>Total Seats* </label>
                  <input class="form-control" type="text" name="total_seats" placeholder="Total Seats">
               </div>
            </div>
            <div class="tts-col-3">
               <label>Business Type</label>
               <label><input class="Lead" type="checkbox" name="b2c_status" placeholder="B2C Status">B2C</label>
               <label><input class="Lead" type="checkbox" name="b2b_status" placeholder="B2B Status">B2B</label>
            </div>
         </div>
      </div>
      <?php $onward_segment_details =  json_decode($details['onward_segment_detail'],true); if($onward_segment_details) {  foreach($onward_segment_details as $onward_segment_detail) { ?>
      <div class="row">
         <div class="col-md-2"><?php echo  ucfirst($onward_segment_detail['origin_airport_code'])."-". ucfirst($onward_segment_detail['destination_airport_code']); ?></div>
         <div class="col-md-2">
            <div class="form-group form-mb-20">
               <label> Flight No.*  </label>
               <input class="form-control" type="text" name="departure_date" placeholder="Flight No.">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group form-mb-20">
               <label> Departure Date & Time*  </label>
               <input class="form-control" type="text" name="pnr" placeholder="Departure Date & Time">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group form-mb-20">
               <label>Arrival Date & Time * </label>
               <input class="form-control" type="text" name="total_purchased_seat" placeholder="Arrival Date & Time">
            </div>
         </div>
      </div>
      <?php } } ?>
      <?php  if($details['journey_type']=="roundtrip")  {$return_segment_details =  json_decode($details['return_segment_details'],true); if($return_segment_details) { foreach($return_segment_details as $return_segment_detail) { ?>
      <div class="row">
         <div class="col-md-2"><?php echo  ucfirst($return_segment_details['origin_airport_code'])."-". ucfirst($return_segment_detail['destination_airport_code']); ?></div>
         <div class="col-md-2">
            <div class="form-group form-mb-20">
               <label> Flight No.*  </label>
               <input class="form-control" type="text" name="departure_date" placeholder="Flight No.">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group form-mb-20">
               <label> Departure Date & Time*  </label>
               <input class="form-control" type="text" name="pnr" placeholder="Departure Date & Time">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group form-mb-20">
               <label>Arrival Date & Time * </label>
               <input class="form-control" type="text" name="total_purchased_seat" placeholder="Arrival Date & Time">
            </div>
         </div>
      </div>
      <?php } }} ?>

      <div class="modal-footer">
          <button class="btn btn-primary" type="submit">Save</button>
      </div>
   </form>
</div>