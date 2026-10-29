<?php
         $timePicker = time_picker();
         
         $oneway_key = 0;
       
        
         foreach ($segment_detail as  $tripkey=>$segment) {  $tripId  =  "segment-itinerary-html"; if($tripkey==1){  $tripId  =  "return-segment-itinerary-html";} ?>
                     <?php if($tripkey==1){?><div   tts-call-put-html  =  "true"> <?php } ?>
                       
                     <div class="row align-items-center mb-3">
                           <div class="col-md-9">
                              <h5 class="dash-borderRadius main-heading-content">
                                 Trip <?php echo  ($tripkey+1); ?>
                              </h5>
                           </div>
                           <div class="col-md-3 text-md-right">
                           <button type="button" class="badge badge-wt" onclick='add_more_items_segment(event,"<?php echo  $tripId; ?>",15,"<?php echo site_url(); ?>","<?php echo  $tripkey; ?>")'>
                            <i class="fa-solid fa-add"></i> Add More Segment
                           </button>
                        </div>
                     </div>
                 
                     
<div id="<?php echo  $tripId; ?>">
<div class="tts-itinerary-row">
         <?php $onward_stops = count($segment);
         for ($i = 0; $i < $onward_stops; $i++) {
             $origin_airport_code = null;
             $destination_airport_code = null;
             $airline_code = null;
             $origin_terminal = null;
             $destination_terminal = null;
             $flight_number = null;
             $departure_time = null;
             $arrival_time = null;
             $aircraft = null;
             $is_next_day_arrival = null;
             if (isset($segment_detail[$tripkey][$i]['origin_airport_code'])) {
         
                 $origin_airport_code = $segment_detail[$tripkey][$i]['origin_airport_code'];
         
             }
         
             if (isset($segment_detail[$tripkey][$i]['destination_airport_code'])) {
         
                 $destination_airport_code = $segment_detail[$tripkey][$i]['destination_airport_code'];
         
             }
         
             if (isset($segment_detail[$tripkey][$i]['airline_code'])) {
         
                 $airline_code = $segment_detail[$tripkey][$i]['airline_code'];
         
             }
         
             if (isset($segment_detail[$tripkey][$i]['origin_terminal'])) {
         
                 $origin_terminal = $segment_detail[$tripkey][$i]['origin_terminal'];
         
             }
         
             if (isset($segment_detail[$tripkey][$i]['destination_terminal'])) {
         
                 $destination_terminal = $segment_detail[$tripkey][$i]['destination_terminal'];
         
             }
         
         
         
             if (isset($segment_detail[$tripkey][$i]['flight_number'])) {
         
                 $flight_number = $segment_detail[$tripkey][$i]['flight_number'];
         
             }
             if (isset($segment_detail[$tripkey][$i]['Craft'])) {
         
                 $aircraft = $segment_detail[$tripkey][$i]['Craft'];
         
             }
         
         
         
             if (isset($segment_detail[$tripkey][$i]['departure_time'])) {
         
                 $departure_time = $segment_detail[$tripkey][$i]['departure_time'];
         
             }
         
         
         
             if (isset($segment_detail[$tripkey][$i]['arrival_time'])) {
         
                 $arrival_time = $segment_detail[$tripkey][$i]['arrival_time'];
         
             }
         
         
         
             if (isset($segment_detail[$tripkey][$i]['is_next_day_arrival'])) {
         
                 $is_next_day_arrival = $segment_detail[$tripkey][$i]['is_next_day_arrival'];
         
             }
         
         
         
         
         
             ?>

   <div class="row align-items-center mb-3">
      <div class="col-md-11">
         <h3 class="dash-borderRadius main-heading-content m0">Segment Information</h3>
      </div>
      <div class="col-md-1 text-md-right">
         <?php  if($i!=0) { ?>
         <span class="action fa-solid fa-trash" onclick="remove_more_items_segment(this,'<?php echo  $tripId; ?>')"></span>
         <?php } ?>
      </div>
   </div>
   <div class="row ">
      <div class="col-md-4">
         <div class="form-group form-mb-20 ">
            <label>Origin Airport Code *</label>
            <input class="form-control" type="text" tts-get-single-airport="true"
               name="onward[<?php echo $tripkey; ?>][<?php echo $i; ?>][origin_airport_code]"
               placeholder="Origin Airport Code" value="<?php echo $origin_airport_code; ?>">
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Origin Terminal </label>
            <input class="form-control" type="text"
               name="onward[<?php echo $tripkey; ?>][<?php echo $i; ?>][origin_terminal]"
               placeholder="Origin Terminal" value="<?php echo $origin_terminal; ?>">
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Destination Airport Code *</label>
            <input class="form-control" type="text" tts-get-single-airport="true"
               name="onward[<?php echo $tripkey; ?>][<?php echo $i; ?>][destination_airport_code]"
               placeholder="Destination Airport Code" value="<?php echo $destination_airport_code; ?>">
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Destination Terminal </label>
            <input class="form-control" type="text"
               name="onward[<?php echo $tripkey; ?>][<?php echo $i; ?>][destination_terminal]"
               placeholder="Origin Terminal" value="<?php echo $destination_terminal; ?>">
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Airline Code *</label>
            <input class="form-control" type="text" tts-get-airline="true"
               name="onward[<?php echo $tripkey; ?>][<?php echo $i; ?>][airline_code]"
               placeholder="Airline Code" value="<?php echo $airline_code; ?>">
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Flight Number *</label>
            <input class="form-control" type="text"
               name="onward[<?php echo $tripkey; ?>][<?php echo $i; ?>][flight_number]"
               placeholder="Flight Number" value="<?php echo $flight_number; ?>">
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Aircraft</label>
            <input class="form-control" type="text"
               name="onward[<?php echo $tripkey; ?>][<?php echo $i; ?>][Craft]"
               placeholder="Aircraft" value="<?php echo $aircraft; ?>">
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Departure Time *</label>
            <select class="form-control" name="onward[<?php echo $tripkey; ?>][<?php echo $i; ?>][departure_time]">
               <option value="" selected>Departure Time</option>
               <?php foreach ($timePicker as $key => $time) { ?>
               <option value="<?php echo $time; ?>" <?php if ($time == $departure_time) {
                  echo 'selected';
                  
                  } ?> ><?php echo $time; ?></option>
               <?php } ?>
            </select>
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Arrival Time *</label>
            <select class="form-control" name="onward[<?php echo $tripkey; ?>][<?php echo $i; ?>][arrival_time]">
               <option value="" selected>Arrival Time</option>
               <?php foreach ($timePicker as $key => $time) { ?>
               <option value="<?php echo $time; ?>" <?php if ($time == $arrival_time) {
                  echo 'selected';
                  
                  } ?>><?php echo $time; ?></option>
               <?php } ?>
            </select>
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Is Arrival Next Day?</label>
            <select class="form-control" name="onward[<?php echo $tripkey; ?>][<?php echo $i; ?>][is_next_day_arrival]"
               placeholder="Is Arrival Next Day?">
               <option value="No" <?php if ($is_next_day_arrival == 'No') {
                  echo 'selected';
                  
                  } ?> >No
               </option>
               <option value="Yes" <?php if ($is_next_day_arrival == 'Yes') {
                  echo 'selected';
                  
                  } ?>>Yes
               </option>
            </select>
         </div>
      </div>
   </div>


<?php } ?></div></div><?php if($tripkey==1){?></div> <?php } ?><?php  } ?>