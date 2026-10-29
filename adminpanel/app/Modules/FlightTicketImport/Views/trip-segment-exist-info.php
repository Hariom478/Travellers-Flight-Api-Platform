<?php if($tripsegmentInfo) {  foreach(
   $tripsegmentInfo as $TripIndicatorkey=>$tripsegment){  
       $TripIndicator =  ($TripIndicatorkey+1);
       $segmentIndicator  = (count($tripsegment)-1);
       
       ?>
<div class="view_head mb_10">
   <div class="row">
      <div class="col-md-12">
         <span>Trip <?php echo  $TripIndicator;?></span>
      </div>
   </div>
</div>
<div class="table_title">
   <div class="view_head">
      <div class="row">
         <div class="col-md-2">
            <span>Segment Details</span>
         </div>
      </div>
   </div>
</div>
<div class="row" tts-call-put-segment-html-<?php echo  $TripIndicator?>="true">
   <?php
      if($tripsegment) {
      foreach($tripsegment as $segmentkey=>$segment) {
      $i =  $segmentkey;?>
   <div class="tts-d-content" id="tts-segment-html">
      <div class="tts-d-content tts-segment-row">
         <div class="col-md-12 ">
            <h6>
               <samp class="badge badge-md badge-primary">Segment Info</samp>
               <?php if ($i == 0) { ?>
               <span style="color:red;margin-left:10px;">Stop <?php echo $i; ?> </span> Means NonStop Flight
               <?php } else { ?>
               <span style="color:red;margin-left:10px;">Stop <?php echo $i; ?> </span>
               <?php } ?>
            </h6>
         </div>
         <div class="col-md-2">
            <div class="form-group">
               <label>Airline PNR </label>
               <input class="form-control" type="text" name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][airline_pnr]"
                  placeholder="Airline PNR"  value  =  "<?php echo isset($segment['AirlinePNR'])?$segment['AirlinePNR']:"";  ?>">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
               <label>Origin *</label>
               <input class="form-control" type="text" name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][origin_airport_code]"
                  tts-get-single-airport="true"
                  placeholder="Origin"  value  =  "<?php echo $segment['Origin']['AirportCode'];  ?>">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
               <label>Destination *</label>
               <input class="form-control" type="text" name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][destination_airport_code]"
                  tts-get-single-airport="true"
                  placeholder="Destination"  value  =  "<?php echo $segment['Destination']['AirportCode'];  ?>">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
               <label>Departure Date *</label>
               <input class="form-control" type="text" name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][depart_date]"
                  placeholder="Departure Date" harish-upload-import-from-date =  "true" value  =  "<?php echo    date("d-m-Y",strtotime(str_replace("T"," ",$segment['Origin']['DepartTime'])));  ?>">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
               <label>Departure Time *</label>
               <input class="form-control" type="text" name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][depart_time]"
                  placeholder="Departure Time "  value  =  "<?php echo    date("H:i",strtotime(str_replace("T"," ",$segment['Origin']['DepartTime'])));  ?>">
               <span class  =  "text-info">Enter Time Like this 20:00</span>
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
               <label>Arrival Date *</label>
               <input class="form-control" type="text" name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][arrival_date]"
                  placeholder="Arrival Date"  harish-upload-import-to-date =  "true" value  =  "<?php echo date("d-m-Y",strtotime(str_replace("T"," ",$segment['Destination']['ArrivalTime'])));  ?>">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
               <label>Arrival Time *</label>
               <input class="form-control" type="text" name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][arrival_time]"
                  placeholder="Arrival Time"   value  =  "<?php echo date("H:i",strtotime(str_replace("T"," ",$segment['Destination']['ArrivalTime'])));  ?>">
               <span class  =  "text-info">Enter Time Like this 20:00</span>
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
               <label>Airline Code *</label>
               <input class="form-control" type="text" tts-get-airline="true"
                  name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][airline_code]"
                  placeholder="Airline Code" value  =  "<?php echo $segment['Airline']['AirlineCode']."-".$segment['Airline']['AirlineName'];  ?>">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
               <label>Flight Number * </label>
               <input class="form-control" type="text"
                  name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][flight_number]"
                  placeholder="Flight Number" value  =  "<?php echo $segment['Airline']['FlightNumber'];  ?>">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
               <label>Origin Terminal </label>
               <input class="form-control" type="text"
                  name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][origin_terminal]"
                  placeholder="Origin Terminal" value  =  "<?php echo $segment['Origin']['Terminal'];  ?>">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
               <label>Destination Terminal </label>
               <input class="form-control" type="text"
                  name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][destination_terminal]"
                  placeholder="Origin Terminal" value  =  "<?php echo $segment['Destination']['Terminal'];  ?>">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
               <label>Craft </label>
               <input class="form-control" type="text"
                  name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][craft]"
                  placeholder="Craft" value  =  "<?php echo $segment['Craft'];  ?>">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
               <label>Fare Class </label>
               <input class="form-control" type="text"
                  name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][fare_class]"
                  placeholder="Fare Class" value  =  "<?php echo $segment['Airline']['FareClass'];  ?>">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
               <label>Fare Basis </label>
               <input class="form-control" type="text"
                  name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][fare_basis]"
                  placeholder="Fare Basis" value  =  "<?php echo isset($segment['Airline']['FareBasisCode'])?$segment['Airline']['FareBasisCode']:"";  ?>">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
               <label>CheckIn Baggage </label>
               <input class="form-control" type="text"
                  name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][baggage]"
                  placeholder="CheckIn Baggage" value  =  "<?php echo $segment['CheckInBaggage'];  ?>">
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
               <label>Cabin Baggage </label>
               <input class="form-control" type="text"
                  name="segmentinfo[<?php echo  $TripIndicator; ?>][<?php echo $i; ?>][cabin_baggage]"
                  placeholder="Cabin Baggage" value  =  "<?php echo $segment['CabinBaggage'];  ?>">
            </div>
         </div>
      </div>
   </div>
   <?php } }  ?>
</div>
<?php } } ?>