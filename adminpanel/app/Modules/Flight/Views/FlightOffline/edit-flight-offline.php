<div class="modal-header">
   <h5 class="modal-title">Edit <?php echo 'Flight Offline'; ?></h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="vewmodelhed">
   <form action="<?php echo site_url('flightoffline/edit-flight-offline/' . dev_encode($id)); ?>" method="post"
      tts-form="true" name="edit_flight_offline">
      <div class="modal-body">
         <div class="row">
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Supplier *</label>
                  <select class="form-control select_search" name="supplier[]" multiple="multiple">
                     <?php if ($supplier_list) {
                        foreach ($supplier_list as $list) { ?>
                           <option value="<?php echo $list['supplier_name'] ?>" <?php if (in_array($list['supplier_name'], $details['supplier'])) {
                                echo "selected";

                             } ?>><?php echo $list['supplier_name'] ?></option>
                        <?php }
                     } ?>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>No of Days to Hold/Pending * </label>
                  <input class="form-control" type="number" name="departure_days"
                     value="<?php echo $details['departure_days'] ?>" placeholder="No of Days to Hold/Pending ">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Airline Code * </label>
                  <input class="form-control" type="text" tts-get-airline="true"
                     value="<?php echo $details['airline_code'] . '-' . $details['airline_name'] ?>" name="airline_code"
                     placeholder="Airline Code">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>From Airport *</label>
                  <input class="form-control" value="<?php echo $details['from_airport_code'] ?>" type="text"
                     tts-get-single-airport="true" name="from_airport_code" placeholder="From Airport">
               </div>
            </div>
            <div class="col-md-6 align-self-end">
               <div class="form-group form-mb-20">
                  <label>
                     <input type="checkbox" name="" tts-from-any="true" value="ANY" class="Lead"
                        <?= ($details['from_airport_code'] == 'ANY') ? 'checked' : '' ?>>From Any Airport
                  </label>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>To Airport * </label>
                  <input class="form-control" type="text" tts-get-single-airport="true"
                     value="<?php echo $details['to_airport_code'] ?>" name="to_airport_code" placeholder="To Airport">
               </div>
            </div>
            <div class="col-md-6 align-self-end">
               <div class="form-group form-mb-20">
                  <label>
                     <input type="checkbox" name="" tts-to-any="true" value="ANY" class="Lead"
                        <?= ($details['to_airport_code'] == 'ANY') ? 'checked' : '' ?>>To Any Airport
                  </label>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>Booking Class * </label>
                  <input class="form-control" type="text" name="booking_class" placeholder="Like A,B"
                     value="<?php echo $details['booking_class'] ?>">
               </div>
            </div>
            <div class="col-md-6 align-self-end">
               <div class="form-group form-mb-20">
                  <label>
                     <input type="checkbox" name="" tts-booking-any="true" value="ANY" class="Lead"
                        <?= ($details['booking_class'] == 'ANY') ? 'checked' : '' ?>>Any Booking Class
                  </label>
               </div>
            </div>
            <div class="col-md-12">
               <div class="form-group form-mb-20">
                  <label>Fare Type *</label>
                  <select class="form-control select_search" name="faretype[]" multiple="multiple">
                     <?php if (ApiFlighFareType) {
                        foreach (ApiFlighFareType as $data) { ?>
                           <option value="<?php echo $data ?>" <?php if (in_array($data, $details['faretype'])) {
                                 echo "selected";
                              } ?>>
                              <?php echo ucfirst($data); ?>
                           </option>
                        <?php }
                     } ?>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Cabin Class *</label>
                  <select class="form-control select_search" name="cabin_class[]" placeholder="Cabin Class"
                     multiple="multiple">

                     <option value="Economy" <?php if (in_array("Economy", $details['cabin_class'])) {
                        echo "selected";
                     } ?>>Economy</option>
                     <option value="PremiumEconomy" <?php if (in_array("PremiumEconomy", $details['cabin_class'])) {
                        echo "selected";
                     } ?>>Premium Economy</option>
                     <option value="Business" <?php if (in_array("Business", $details['cabin_class'])) {
                        echo "selected";
                     } ?>>Business</option>
                     <option value="First" <?php if (in_array("First", $details['cabin_class'])) {
                        echo "selected";
                     } ?>>
                        First</option>
                     <option value="PremiumBusiness" <?php if (in_array("PremiumBusiness", $details['cabin_class'])) {
                        echo "selected";
                     } ?>>Premium Business</option>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Flight Type *</label>
                  <select class="form-control select_search" name="is_domestic[]" multiple="multiple">
                     <option value="1" <?php if (in_array("1", $details['is_domestic'])) {
                        echo "selected";
                     } ?>>Domestic
                     </option>
                     <option value="0" <?php if (in_array("0", $details['is_domestic'])) {
                        echo "selected";
                     } ?>>
                        International</option>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Status *</label>
                  <select class="form-select" name="status" placeholder="Status">
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
            <div class="col-md-6 align-self-end">
               <div class="form-group form-mb-20">
                  <label for="hold">
                     <input class="" id="hold" type="radio" name="tts_is_hold" value="Hold" <?php if ($details['is_hold'] == "Hold") {
                        echo "checked";

                     } ?>> Hold
                  </label>
                  <label for="pending">
                     <input class="" id="pending" type="radio" name="tts_is_hold" value="Pending" <?php if ($details['is_offline'] == "Pending") {
                        echo "checked";

                     } ?>> Pending
                  </label>
               </div>
               <p class="text-danger">Note:You can hold GDS airline only.</p>
            </div>
         </div>
      </div>
      <div class="modal-footer">
         <button class="btn btn-primary" type="submit">Save</button>
      </div>
   </form>
</div>