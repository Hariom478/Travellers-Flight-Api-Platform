<?php
   $i =  $segmentIndicator;?>
<div class="tts-d-content" id="tts-segment-html">
   <div class="tts-d-content tts-segment-row row">
      <div class="col-md-12 form-mb-20 mb-3">
         <h6 >
            <span class="badge badge-wt">Segment Info</span>
            <!--   <?php if ($i == 0) { ?>
               <span style="color:red;margin-left:10px;">Stop <?php echo $i; ?> </span> Means NonStop Flight
               
               <?php } else { ?>
               
               <span style="color:red;margin-left:10px;">Stop <?php echo $i; ?> </span>
               
               <?php } ?> -->
            <?php if ($i !=0){?>
            <div class="float-end mt_10">
               <span class="action close-icon tts-text-danger cursor-hand"
                  onclick="remove_more_segment(this,'tts-segment-html')">Remove</span>
            </div>
            <?php }?>
         </h6>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Airline PNR </label>
            <input class="form-control" type="text" name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][airline_pnr]"
               placeholder="Airline PNR">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group form-mb-20">
            <label>Origin *</label>
            <input class="form-control" type="text" name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][origin_airport_code]"
               tts-get-single-airport="true"
               placeholder="Origin">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group form-mb-20 ">
            <label>Destination *</label>
            <input class="form-control" type="text" name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][destination_airport_code]"
               tts-get-single-airport="true"
               placeholder="Destination">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Departure Date *</label>
            <input class="form-control" type="text" name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][depart_date]"
               placeholder="Departure Date" harish-upload-import-from-date =  "true">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Departure Time *</label>
            <input class="form-control" type="text" name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][depart_time]"
               placeholder="Departure Time ">
            <span class  =  "text-info">Enter Time Like this 20:00</span>
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Arrival Date *</label>
            <input class="form-control" type="text" name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][arrival_date]"
               placeholder="Arrival Date"  harish-upload-import-to-date =  "true">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Arrival Time *</label>
            <input class="form-control" type="text" name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][arrival_time]"
               placeholder="Arrival Time">
            <span class  =  "text-info">Enter Time Like this 20:00</span>
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group form-mb-20">
            <label>Airline Code *</label>
            <input class="form-control" type="text" tts-get-airline="true"
               name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][airline_code]"
               placeholder="Airline Code">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group form-mb-20">
            <label>Flight Number * </label>
            <input class="form-control" type="text"
               name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][flight_number]"
               placeholder="Flight Number">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group form-mb-20">
            <label>From Terminal </label>
            <input class="form-control" type="text"
               name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][origin_terminal]"
               placeholder="From Terminal">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group form-mb-20">
            <label>To Terminal </label>
            <input class="form-control" type="text"
               name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][destination_terminal]"
               placeholder="To Terminal">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group form-mb-20">
            <label>Craft </label>
            <input class="form-control" type="text"
               name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][craft]"
               placeholder="Craft">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group form-mb-20">
            <label>Fare Class </label>
            <input class="form-control" type="text"
               name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][fare_class]"
               placeholder="Fare Class">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group form-mb-20">
            <label>Fare Basis </label>
            <input class="form-control" type="text"
               name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][fare_basis]"
               placeholder="Fare Basis">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group form-mb-20">
            <label>CheckIn Bag </label>
            <input class="form-control" type="text"
               name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][baggage]"
               placeholder="CheckIn Bag">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group form-mb-20">
            <label>Cabin Bag </label>
            <input class="form-control" type="text"
               name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][cabin_baggage]"
               placeholder="Cabin Bag">
         </div>
      </div>
   </div>
</div>