<div class="modal-header">
   <h5 class="modal-title">Add <?php echo 'Flight Offline'; ?></h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="<?php echo site_url('flightoffline/flight-offline'); ?>" method="post" tts-form="true"
   name="add_flight_offline">
   <div class="modal-body">
      <div class="row align-items-center">
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Supplier *</label>
               <select class="form-select select_search" name="supplier[]" multiple="multiple">
                  <?php if ($supplier_list) {
                     foreach ($supplier_list as $data) { ?>
                        <option value="<?php echo $data['supplier_name'] ?>">
                           <?php echo ucfirst($data['supplier_name']); ?>
                        </option>
                     <?php }
                  } ?>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>No of Days to Hold/Pending * </label>
               <input class="form-control" type="number" name="departure_days"
                  placeholder="No of Days to Hold/Pending ">
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Airline Code * </label>
               <input class="form-control" type="text" tts-get-airline="true" value="ANY-Any Airline"
                  name="airline_code" placeholder="Airline Code">
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label>From Airport *</label>
               <input class="form-control" type="text" tts-get-single-airport="true" name="from_airport_code"
                  placeholder="From Airport">
            </div>
         </div>
         <div class="col-md-6 align-self-end">
            <div class="form-group form-mb-20">
               <label>
                  <input type="checkbox" name="" tts-from-any="true" value="ANY" class="Lead">From Any Airport
               </label>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label>To Airport * </label>
               <input class="form-control" type="text" tts-get-single-airport="true" name="to_airport_code"
                  placeholder="To Airport">
            </div>
         </div>
         <div class="col-md-6 align-self-end">
            <div class="form-group form-mb-20">
               <label>
                  <input type="checkbox" name="" tts-to-any="true" value="ANY" class="Lead">To Any Airport
               </label>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group form-mb-20">
               <label>Booking Class * </label>
               <input class="form-control" type="text" name="booking_class" placeholder="Like A,B">
            </div>
         </div>



         <div class="col-md-6 align-self-end">
            <div class="form-group form-mb-20">
               <label>
                  <input type="checkbox" name="" tts-booking-any="true" value="ANY" class="Lead">Any Booking Class
               </label>
            </div>
         </div>
         <div class="col-md-12">
            <div class="form-group form-mb-20">
               <label>Fare Type *</label>
               <select class="form-select select_search" name="faretype[]" multiple="multiple">
                  <?php if (ApiFlighFareType) {
                     foreach (ApiFlighFareType as $data) { ?>
                        <option value="<?php echo $data ?>">
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
               <select class="form-select select_search" name="cabin_class[]" placeholder="Cabin Class"
                  multiple="multiple">
                  <option value="Economy">Economy</option>
                  <option value="PremiumEconomy">Premium Economy</option>
                  <option value="Business">Business</option>
                  <option value="First">First</option>
                  <option value="PremiumBusiness">Premium Business</option>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Flight Type *</label>
               <select class="form-select select_search" name="is_domestic[]" placeholder="Flight Type"
                  multiple="multiple">
                  <option value="1" selected>Domestic</option>
                  <option value="0">International</option>
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Status *</label>
               <select class="form-select" name="status" placeholder="Status">
                  <option value="active">Active</option>
                  <option value="inactive">Inactive </option>
               </select>
            </div>
         </div>
         <div class="col-md-6 align-self-end">
            <div class="form-group form-mb-20">
               <label for="hold">
                  <input class="" id="hold" type="radio" name="tts_is_hold" value="Hold" checked=""> Hold
               </label>
               <label for="pending">
                  <input class="" id="pending" type="radio" name="tts_is_hold" value="Pending"> Pending
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