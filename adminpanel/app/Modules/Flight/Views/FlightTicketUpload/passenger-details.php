<?php if(isset($paxDataInfo) && !empty($paxDataInfo)) {  $addingValue = 0;  foreach($paxDataInfo as $paxkey=>$paxData) {  if($paxkey==0){$addingValue= 1;}     $i  = $paxkey+$addingValue;  ?>
<div class="tts-d-content" id="tts-passenger-html">
   <div class="tts-d-content tts-passenger-row row">
      <div class="col-md-12 ">
         <h6>
            <?php if ($i == 1) { ?>
            <span class="tts-text-success">Passenger  </span>
            <?php } else { ?>
            <span class="tts-text-success">Passenger  </span>
            <?php } ?>
            <?php if ($i != 1) { ?>
            <div class="pull-right mt_10">
               <span class="action close-icon tts-text-danger cursor-hand"
                  onclick="remove_more_passenger(this,'tts-passenger-html')">Remove</span>
            </div>
            <?php } ?>
         </h6>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Title *</label>
            <select class="form-select" name="pax[<?php echo $i; ?>][title]">
               <option value="" selected>Select Title</option>
               <option value="Mr" <?php echo  $paxData['title']=="Mr"?"selected":""; ?>>Mr</option>
               <option value="Ms" <?php echo  $paxData['title']=="Ms"?"selected":""; ?>>Ms</option>
               <option value="Mrs" <?php echo  $paxData['title']=="Mrs"?"selected":""; ?>>Mrs</option>
               <option value="Ms" <?php echo  $paxData['title']=="Ms"?"selected":""; ?>>Ms</option>
               <option value="Mstr" <?php echo  $paxData['title']=="Mstr"?"selected":""; ?>>Mstr</option>
            </select>
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>First Name * </label>
            <input class="form-control" type="text"
               name="pax[<?php echo $i; ?>][first_name]"
               placeholder="First Name" value  =  "<?php echo  $paxData['first_name']; ?>">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Last Name * </label>
            <input class="form-control" type="text"
               name="pax[<?php echo $i; ?>][last_name]"
               placeholder="Last Name"  value  =  "<?php echo  $paxData['last_name']; ?>">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Passenger Type *</label>
            <select class="form-select" name="pax[<?php echo $i; ?>][pax_type]">
               <option value="" selected>Select</option>
               <option value="Adult" <?php echo  $paxData['pax_type']=="Adult"?"selected":""; ?>>Adult</option>
               <option value="Child" <?php echo  $paxData['pax_type']=="Child"?"selected":""; ?>>Children</option>
               <option value="Infant" <?php echo  $paxData['pax_type']=="Infant"?"selected":""; ?>>Infant</option>
            </select>
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Gender *</label>
            <select class="form-select" name="pax[<?php echo $i; ?>][gendar]">
               <option value="" selected>Select</option>
               <option value="Male" <?php echo  $paxData['gendar']=="Male"?"selected":""; ?>>Male</option>
               <option value="Female" <?php echo  $paxData['gendar']=="Female"?"selected":""; ?>>Female</option>
            </select>
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Date of Birth </label>
            <input class="form-control" type="text"
               name="pax[<?php echo $i; ?>][date_of_birth]"
               placeholder="Date of Birth" dob-calendor =  "true" value  =  "<?php echo  $paxData['date_of_birth']; ?>">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Pan Number </label>
            <input class="form-control" type="text"
               name="pax[<?php echo $i; ?>][pan_number]"
               placeholder="Pan Number" value  =  "<?php echo  $paxData['pan_number']; ?>">
         </div>
      </div>
      <div class="col-md-2 pr0">
         <div class="form-group">
            <label> Passport Nationality </label>
            <select class="form-select"  name="pax[<?php echo $i; ?>][passport_nationality]">
               <option value="">Select </option>
               <?php $country_codes = get_countary_code();
                  if ($country_codes) {
                  
                      foreach ($country_codes as $country_code) { ?>
               <option value="<?php echo $country_code['countrycode']; ?>">
                  <?php echo $country_code['countryname']; ?>
               </option>
               <?php }
                  } ?>
            </select>
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Passport Number </label>
            <input class="form-control" type="text"
               name="pax[<?php echo $i; ?>][passport_number]"
               placeholder="Passport Number"  value  =  "<?php echo  $paxData['passport_number']; ?>">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Passport Issue Date </label>
            <input class="form-control" type="text" flight_pass_expiry="true"
               name="pax[<?php echo $i; ?>][passport_issue_date]"
               placeholder="Passport Issue Date"  dob-calendor =  "true" value  =  "<?php echo  $paxData['passport_issue_date']; ?>">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Passport Expiry Date</label>
            <input class="form-control" type="text" flight_pass_expiry="true"
               name="pax[<?php echo $i; ?>][passport_expiry]"
               placeholder="Passport Expiry Date"  dob-calendor =  "true"   value  =  "<?php echo  $paxData['passport_expiry']; ?>">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>PNR *</label>
            <input class="form-control" type="text"
               placeholder="PNR" name="pax[<?php echo $i; ?>][pnr]" value  =  "<?php echo  $paxData['pnr']; ?>">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Ticket Number *</label>
            <input class="form-control" type="text"
               placeholder="Ticket Number"   name="pax[<?php echo $i; ?>][ticket_number]" value  =  "<?php echo  $paxData['ticket_number']; ?>">
         </div>
      </div>
      <!--  <div class="col-md-2">
         <br/>
         
         <div class="form-group">
         
             <label>  <input class="" type="checkbox"
         
                    placeholder="Meal/Baggage"   name="pax[<?php echo $i; ?>][meal_bggage]"   pax-key  =  "<?php echo $i; ?>" tts-flight-ticket-upload-meal-seclect =  "true"> Meal/Baggage
         
             </label>
         
         </div>
         
         </div>
         -->
      <div class  =  "col-md-12 hide" pax_<?php echo $i; ?> =  "true">
         <div class="col-md-12">
            <h6 class="view_head">SSR Info (Optional)</h6>
         </div>
         <?php if($ssrtripsegmentInfo) {  foreach($ssrtripsegmentInfo as $ssrtripsegment) {
            foreach($ssrtripsegment as $segment) {  $SegmentCities  =  $segment['orgin']."-".$segment['destination'];
            
            $journeyfirstSegment  =  reset($ssrtripsegment);
            
            $journeylastSegment  =  end($ssrtripsegment);
            
            $jouneyCities  =  $journeyfirstSegment['orgin']."-".$journeyfirstSegment['destination'];
            
            
            
            ?>
         <div class="col-md-12">
            <samp class="btn btn-primary"><?php echo   $SegmentCities; ?></samp>
         </div>
         <div class  = "row m0">
            <div class="col-md-2">
               <div class="form-group">
                  <label>Baggage Charges  </label>
                  <input class="form-control" type="text"
                     name="pax[<?php echo $i; ?>][baggage][<?php echo   $jouneyCities; ?>][amount]"
                     placeholder="Baggage Charges">
               </div>
            </div>
            <div class="col-md-2">
               <div class="form-group">
                  <label>Baggage  </label>
                  <input class="form-control" type="text"
                     name="pax[<?php echo $i; ?>][baggage][<?php echo   $jouneyCities; ?>][quantity]"
                     placeholder="Baggage Quantity">
               </div>
            </div>
            <div class="col-md-2">
               <div class="form-group">
                  <label>Seat Charges </label>
                  <input class="form-control" type="text"
                     placeholder="Seat Charges" name="pax[<?php echo $i; ?>][seat][<?php echo   $SegmentCities; ?>][amount]">
               </div>
            </div>
            <div class="col-md-2">
               <div class="form-group">
                  <label>Seat Number </label>
                  <input class="form-control" type="text"
                     placeholder="Seat Number" name="pax[<?php echo $i; ?>][seat][<?php echo   $SegmentCities; ?>][seat_number]">
               </div>
            </div>
            <div class="col-md-2">
               <div class="form-group">
                  <label>Meal Charges   </label>
                  <input class="form-control" type="text"
                     placeholder="Meal Charges"  name="pax[<?php echo $i; ?>][meal][<?php echo   $SegmentCities; ?>][amount]">
               </div>
            </div>
            <div class="col-md-2">
               <div class="form-group">
                  <label>Meal Name</label>
                  <input class="form-control" type="text"
                     placeholder="Meal Name"  name="pax[<?php echo $i; ?>][meal][<?php echo   $SegmentCities; ?>][name]">
               </div>
            </div>
         </div>
         <?php } ?> 
         <div class  = "col-md-12">
            <h6 class=  "tts-flight-upload-border"></h6>
         </div>
         <?php  }  }  ?>
      </div>
   </div>
</div>
<?php  } } else { $i  =  $passengerCounter;  ?>
<div class="tts-d-content" id="tts-passenger-html">
   <div class="tts-d-content tts-passenger-row row">
      <div class="col-md-12 ">
         <h6>
            <?php if ($i == 1) { ?>
            <span class="tts-text-success">Passenger  </span>
            <?php } else { ?>
            <span class="tts-text-success">Passenger  </span>
            <?php } ?>
            <?php if ($i != 1) { ?>
            <div class="pull-right mt_10">
               <span class="action close-icon tts-text-danger cursor-hand"
                  onclick="remove_more_passenger(this,'tts-passenger-html','<?php echo  $pax_type; ?>')">Remove</span>
            </div>
            <?php } ?>
         </h6>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Title *</label>
            <select class="form-select" name="pax[<?php echo $i; ?>][title]">
               <option value="" selected>Select Title</option>
               <option value="Mr">Mr</option>
               <option value="Ms">Ms</option>
               <option value="Mrs">Mrs</option>
               <option value="Ms">Ms</option>
               <option value="Mstr">Mstr</option>
            </select>
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>First Name * </label>
            <input class="form-control" type="text"
               name="pax[<?php echo $i; ?>][first_name]"
               placeholder="First Name">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Last Name * </label>
            <input class="form-control" type="text"
               name="pax[<?php echo $i; ?>][last_name]"
               placeholder="Last Name">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Passenger Type *</label>
            <select class="form-select" name="pax[<?php echo $i; ?>][pax_type]" readonly>
               <option value="" selected>Select</option>
               <option value="Adult" <?php echo  $pax_type  ==  "Adult"?"selected":""; ?>>Adult</option>
               <option value="Child" <?php echo $pax_type  ==  "Child"?"selected":""; ?>>Children</option>
               <option value="Infant" <?php echo $pax_type  ==  "Infant"?"selected":""; ?>>Infant</option>
            </select>
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Gender *</label>
            <select class="form-select" name="pax[<?php echo $i; ?>][gendar]">
               <option value="" selected>Select</option>
               <option value="Male">Male</option>
               <option value="Female">Female</option>
            </select>
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Date of Birth </label>
            <input class="form-control" type="text"
               name="pax[<?php echo $i; ?>][date_of_birth]"
               placeholder="Date of Birth" dob-calendor =  "true">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Pan Number </label>
            <input class="form-control" type="text"
               name="pax[<?php echo $i; ?>][pan_number]"
               placeholder="Pan Number">
         </div>
      </div>
      <div class="col-md-2 pr0">
         <div class="form-group">
            <label> Passport Nationality </label>
            <select class="form-select"  name="pax[<?php echo $i; ?>][passport_nationality]">
               <option value="">Select </option>
               <?php $country_codes = get_countary_code();
                  if ($country_codes) {
                  
                      foreach ($country_codes as $country_code) { ?>
               <option value="<?php echo $country_code['countrycode']; ?>">
                  <?php echo $country_code['countryname']; ?>
               </option>
               <?php }
                  } ?>
            </select>
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Passport Number </label>
            <input class="form-control" type="text"
               name="pax[<?php echo $i; ?>][passport_number]"
               placeholder="Passport Number">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Passport Issue Date </label>
            <input class="form-control" type="text" flight_pass_expiry="true"
               name="pax[<?php echo $i; ?>][passport_issue_date]"
               placeholder="Passport Issue Date"  dob-calendor =  "true">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Passport Expiry Date</label>
            <input class="form-control" type="text" flight_pass_expiry="true"
               name="pax[<?php echo $i; ?>][passport_expiry]"
               placeholder="Passport Expiry Date"  dob-calendor =  "true">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>PNR *</label>
            <input class="form-control" type="text"
               placeholder="PNR" name="pax[<?php echo $i; ?>][pnr]">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Ticket Number *</label>
            <input class="form-control" type="text"
               placeholder="Ticket Number"   name="pax[<?php echo $i; ?>][ticket_number]">
         </div>
      </div>
      <!--         <div class="col-md-2">
         <br/>
         
         <div class="form-group">
         
             <label>  <input class="" type="checkbox"
         
                    placeholder="Meal/Baggage"   name="pax[<?php echo $i; ?>][meal_bggage]"   pax-key  =  "<?php echo $i; ?>" tts-flight-ticket-upload-meal-seclect =  "true"> Meal/Baggage
         
             </label>
         
         </div>
         
         </div>
         -->
      <div class  =  "col-md-12 hide" pax_<?php echo $i; ?> =  "true">
         <div class="col-md-12">
            <h6 class="view_head">SSR Info (Optional)</h6>
         </div>
         <?php if($ssrtripsegmentInfo) {  foreach($ssrtripsegmentInfo as $ssrtripsegment) {
            foreach($ssrtripsegment as $segment) {  $SegmentCities  =  $segment['orgin']."-".$segment['destination'];
            
            $journeyfirstSegment  =  reset($ssrtripsegment);
            
            $journeylastSegment  =  end($ssrtripsegment);
            
            $jouneyCities  =  $journeyfirstSegment['orgin']."-".$journeyfirstSegment['destination'];
            
            
            
            ?>
         <div class="col-md-12">
            <samp class="btn btn-primary"><?php echo   $SegmentCities; ?></samp>
         </div>
         <div class  = "row m0">
            <div class="col-md-2">
               <div class="form-group">
                  <label>Baggage Charges  </label>
                  <input class="form-control" type="text"
                     name="pax[<?php echo $i; ?>][baggage][<?php echo   $jouneyCities; ?>][amount]"
                     placeholder="Baggage Charges">
               </div>
            </div>
            <div class="col-md-2">
               <div class="form-group">
                  <label>Baggage  </label>
                  <input class="form-control" type="text"
                     name="pax[<?php echo $i; ?>][baggage][<?php echo   $jouneyCities; ?>][quantity]"
                     placeholder="Baggage Quantity">
               </div>
            </div>
            <div class="col-md-2">
               <div class="form-group">
                  <label>Seat Charges </label>
                  <input class="form-control" type="text"
                     placeholder="Seat Charges" name="pax[<?php echo $i; ?>][seat][<?php echo   $SegmentCities; ?>][amount]">
               </div>
            </div>
            <div class="col-md-2">
               <div class="form-group">
                  <label>Seat Number </label>
                  <input class="form-control" type="text"
                     placeholder="Seat Number" name="pax[<?php echo $i; ?>][seat][<?php echo   $SegmentCities; ?>][seat_number]">
               </div>
            </div>
            <div class="col-md-2">
               <div class="form-group">
                  <label>Meal Charges   </label>
                  <input class="form-control" type="text"
                     placeholder="Meal Charges"  name="pax[<?php echo $i; ?>][meal][<?php echo   $SegmentCities; ?>][amount]">
               </div>
            </div>
            <div class="col-md-2">
               <div class="form-group">
                  <label>Meal Name</label>
                  <input class="form-control" type="text"
                     placeholder="Meal Name"  name="pax[<?php echo $i; ?>][meal][<?php echo   $SegmentCities; ?>][name]">
               </div>
            </div>
         </div>
         <?php } ?> 
         <div class  = "col-md-12">
            <h6 class=  "tts-flight-upload-border"></h6>
         </div>
         <?php  }  }  ?>
      </div>
   </div>
</div>
<?php  } ?>