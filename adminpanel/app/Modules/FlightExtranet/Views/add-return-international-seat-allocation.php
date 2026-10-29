
<div class="modal-header">
        <h5 class="modal-title">Add Seat Allocations</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        
        </button>
      </div>
<form  action="<?php echo site_url('private-fare/add-return-international-seat-allocation/') .dev_encode($private_fare_id); ?>" method="post" tts-form="true" name="add_blogs">      
   <div class="modal-body">
   <div class="row">   
      <div class="col-md-12">
         <h5 class="search__form__section__title">Allocation info</h5>
      </div>
      
     
      
      <div class="col-md-3">
         <div class="form-group form-mb-20">
            <label >Onward Date</label>
            <input type="text" class="form-control" value="<?php echo date('d-M-y')?>"  praveen-from-date="true" name="date"  placeholder="Onward (Date)">
         </div>
      </div>
      <div class="col-md-3">
         <div class="form-group form-mb-20">
            <label for="">Reurn Date</label>
            <input type="text" class="form-control" value="<?php echo date('d-M-y')?>"  praveen-to-date="true" name="date_return" placeholder="Reurn (Date)">
         </div>
      </div>
      <div class="col-md-3">
         <div class="form-group form-mb-20">
            <label for="seats-available">Seats Available</label>
            <input type="text" class="form-control" name="available_seats" id="seats-available" placeholder="Seats Available">
         </div>
      </div>
      <div class="col-md-3">
         <div class="form-group form-mb-20">
            <label for="pnr">Airline PNR</label>
            <input type="text" class="form-control" name="pnr" id="pnr" placeholder="Airline PNR">
         </div>
      </div>
      <div class="col-md-3">
            <div class="form-group form-mb-20">
               <label for="booking-class">Booking Class</label>
               <input type="text" id="booking-class" class="form-control" name="booking_class" placeholder="Booking Class" value="">
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group form-mb-20">
               <label>Cabin Class</label>
               <select class="form-control " name="cabin_class" id="cabin-class">
                  <option value="Economy" selected>Economy</option>
                  <option value="PremiumEconomy">Premium Economy</option>
                  <option value="Business">Business</option>
                  <option value="PremiumBusiness">Premium Business</option>
                  <option value="First">First</option>
               </select>
            </div>
         </div>
       
      <div class  =  "col-md-12">
      <div class="row">
         <div class="col-md-12">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <span class="rate-plan__details__label">Adult</span>
               </div>
               <div class="col-md-4">
                  <div class="form-group form-mb-20">
                     <label for="from-date">Base Fare</label>
                     <input type="text" class="form-control" name="adult_base_fare" placeholder="Base Fare">
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group form-mb-20">
                     <label for="from-date">Tax</label>
                     <input type="text" class="form-control" name="adult_tax" placeholder="Tax">
                  </div>
               </div>
               <!--  <div class="col-md-4">
                  <div class="form-group form-mb-20">
                  
                      <label for="from-date">GST</label>
                  
                      <input type="text" class="form-control" name="adult_gst" placeholder="GSt">
                  
                      
                  
                  </div>
                  
                  </div> -->
            </div>
         </div>
         <div class="col-md-12">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <span class="rate-plan__details__label">Child</span>
               </div>
               <div class="col-md-4">
                  <div class="form-group form-mb-20">
                     <label for="from-date">Base Fare</label>
                     <input type="text" class="form-control" name="child_base_fare" placeholder="Base Fare">
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group form-mb-20">
                     <label for="from-date">Tax</label>
                     <input type="text" class="form-control" name="child_tax" placeholder="Tax">
                  </div>
               </div>
               <!--  <div class="col-md-4">
                  <div class="form-group form-mb-20">
                  
                       <label for="from-date">GST</label>
                  
                      <input type="text" class="form-control" name="child_gst" placeholder="GSt">
                  
                     
                  
                  </div>
                  
                  </div> -->
            </div>
         </div>
         <div class="col-md-12">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <span class="rate-plan__details__label">Infant</span>
               </div>
               <div class="col-md-4">
                  <div class="form-group form-mb-20">
                     <label for="from-date">Base Fare</label>
                     <input type="text" class="form-control" name="infant_base_fare" placeholder="Base Fare">
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group form-mb-20">
                     <label for="from-date">Tax</label>
                     <input type="text" class="form-control" name="infant_tax" placeholder="Tax">
                  </div>
               </div>
               <!--  <div class="col-md-4">
                  <div class="form-group form-mb-20">
                  
                      <label for="from-date">GST</label>
                  
                      <input type="text" class="form-control" name="infant_gst" placeholder="GSt">
                  
                      
                  
                  </div>
                  
                  </div> -->
            </div>
         </div>
      <?php
         $timePicker = time_picker();
         
         $oneway_key = 0;
         
        
         foreach ($segment_detail as  $tripkey=>$segment) {
            echo  '<div class="col-md-12">
            <h5 class="search__form__section__title">Trip '.($tripkey+1).'</h5>
         </div>';
            $onward_stops = count($segment);
         for ($i = 0; $i < $onward_stops; $i++) {
             $origin_airport_code = null;
             $destination_airport_code = null;
             $airline_code = null;
             $origin_terminal = null;
             $destination_terminal = null;
             $flight_number = null;
             $departure_time = null;
             $arrival_time = null;
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
      <div class="col-md-12">
         <h5 class="search__form__section__title">Segment Information</h5>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Origin Airport Code *</label>
            <input class="form-control" type="text"
               placeholder="Origin Airport Code" value="<?php echo $origin_airport_code;?>" disabled>
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Destination Airport Code *</label>
            <input class="form-control" type="text"
               placeholder="Destination Airport Code" value="<?php echo $destination_airport_code;?>" disabled>
         </div>
      </div>
      <div class="col-md-4">
         <div class="form-group form-mb-20">
            <label>Airline Code *</label>
            <input class="form-control" type="text"
               placeholder="Airline Code" value="<?php echo $airline_code;?>" disabled>
         </div>
      </div>
      <div class="col-md-3">
         <div class="form-group form-mb-20">
            <label>Flight Number </label>
            <input class="form-control" type="text"
               name="onward[<?php echo $tripkey;?>][<?php echo $i;?>][flight_number]"
               placeholder="Flight Number" value="<?php echo $flight_number;?>"  >
         </div>
      </div>
      <div class="col-md-3">
         <div class="form-group form-mb-20">
            <label>Departure Time</label>
            <select class="form-control"  name="onward[<?php echo $tripkey;?>][<?php echo $i;?>][departure_time]" >
               <option value="" selected >Departure Time</option>
               <?php foreach ($timePicker as $key=>$time){?>
               <option value="<?php echo $time;?>" <?php if ($time==$departure_time){ echo 'selected';}?> ><?php echo $time;?></option>
               <?php }?>
            </select>
         </div>
      </div>
      <div class="col-md-3">
         <div class="form-group form-mb-20">
            <label>Arrival Time</label>
            <select class="form-control"  name="onward[<?php echo $tripkey;?>][<?php echo $i;?>][arrival_time]" >
               <option value="" selected >Arrival Time</option>
               <?php foreach ($timePicker as $key=>$time){?>
               <option value="<?php echo $time;?>" <?php if ($time==$arrival_time){ echo 'selected';}?>><?php echo $time;?></option>
               <?php }?>
            </select>
         </div>
      </div>
      <div class="col-md-3">
         <div class="form-group form-mb-20">
            <label>Is Arrival Next Day?</label>
            <select class="form-control" name="onward[<?php echo $tripkey;?>][<?php echo $i;?>][is_next_day_arrival]" placeholder="Is Arrival Next Day?">
               <option value="No" <?php if ($is_next_day_arrival=='No'){ echo 'selected';}?> >No</option>
               <option value="Yes" <?php if ($is_next_day_arrival=='Yes'){ echo 'selected';}?>>Yes</option>
            </select>
         </div>
      </div>
      <?php } }?>
      
  </div>
</div>
<div class="modal-footer col-md-12">
   <button type="submit" class="btn btn-primary">Save Seat Allocation</button>   
</div>

 </form>