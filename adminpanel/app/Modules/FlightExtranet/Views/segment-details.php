<div class="row">
   <?php
   $timePicker = time_picker();

   $oneway_key = 0;



   if (isset($data['load_by_page']) && $data['load_by_page'] == 1) {

      $counter = 0;

   } else {

      $counter = 1;

   }

   for ($i = $counter; $i <= $data['onward_stops']; $i++) { ?>
      <div class="col-md-12">
         <h3 class="dash-borderRadius main-heading-content">Segment Information</h3>
      </div>

      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Origin Airport Code *</label>
            <input class="form-control" type="text" tts-get-single-airport="true"
               name="onward[<?php echo $i; ?>][origin_airport_code]" placeholder="Origin Airport Code">
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Origin Terminal </label>
            <input class="form-control" type="text" name="onward[<?php echo $i; ?>][origin_terminal]"
               placeholder="Origin Terminal">
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Destination Airport Code *</label>
            <input class="form-control" type="text" tts-get-single-airport="true"
               name="onward[<?php echo $i; ?>][destination_airport_code]" placeholder="Destination Airport Code">
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Destination Terminal </label>
            <input class="form-control" type="text" name="onward[<?php echo $i; ?>][destination_terminal]"
               placeholder="Origin Terminal">
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Airline Code *</label>
            <input class="form-control" type="text" tts-get-airline="true" name="onward[<?php echo $i; ?>][airline_code]"
               placeholder="Airline Code">
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Flight Number *</label>
            <input class="form-control" type="text" name="onward[<?php echo $i; ?>][flight_number]"
               placeholder="Flight Number">
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Aircraft</label>
            <input class="form-control" type="text" name="onward[<?php echo $i; ?>][aircraft]" placeholder="Aircraft">
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Departure Time *</label>
            <select class="form-control" name="onward[<?php echo $i; ?>][departure_time]">
               <option value="" selected>Departure Time</option>
               <?php foreach ($timePicker as $key => $time) { ?>
                  <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
               <?php } ?>
            </select>
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Arrival Time *</label>
            <select class="form-control" name="onward[<?php echo $i; ?>][arrival_time]">
               <option value="" selected>Arrival Time</option>
               <?php foreach ($timePicker as $key => $time) { ?>
                  <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
               <?php } ?>
            </select>
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Is Arrival Next Day?</label>
            <select class="form-control" name="onward[<?php echo $i; ?>][is_next_day_arrival]"
               placeholder="Is Arrival Next Day?">
               <option value="No" selected>No</option>
               <option value="Yes">Yes</option>
            </select>
         </div>
      </div>
   <?php } ?>
</div>