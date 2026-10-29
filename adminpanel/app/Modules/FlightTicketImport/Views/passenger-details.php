<?php if(isset($paxDataInfo) && !empty($paxDataInfo)) {  $addingValue = 0;  foreach($paxDataInfo as $paxkey=>$paxData) {  if($paxkey==0){$addingValue= 1;}     $i  = $paxkey+$addingValue;  ?>
<div class="tts-d-content pax_detail" id="tts-passenger-html">
   <div class="tts-d-content tts-passenger-row">
      <div class="col-md-12 ">
         <h6>
            <span class="tts-text-success">Passenger  <?php echo get_pax_type($paxData['PaxType']); ?></span>
         </h6>
      </div>
      <div class="col-md-1">
         <div class="form-group">
            <label>Title *</label>
            <select class="form-control" name="pax[<?php echo $i; ?>][Title]">
               <option value="" selected>Select Title</option>
               <option value="Mr" <?php echo  $paxData['Title']=="Mr"|| $paxData['Title']=="MR"?"selected":""; ?>>Mr</option>
               <option value="Ms" <?php echo  $paxData['Title']=="Ms"?"selected":""; ?>>Ms</option>
               <option value="Mrs" <?php echo  $paxData['Title']=="Mrs"?"selected":""; ?>>Mrs</option>
               <option value="Ms" <?php echo  $paxData['Title']=="Ms"?"selected":""; ?>>Ms</option>
               <option value="Mstr" <?php echo  $paxData['Title']=="Mstr"?"selected":""; ?>>Mstr</option>
            </select>
         </div>
      </div>
      <input class="form-control" type="hidden"
         name="pax[<?php echo $i; ?>][key]"
         value  =  "<?php echo  isset($paxData['Key'])?$paxData['Key']:""; ?>">
      <div class="col-md-2">
         <div class="form-group">
            <label>First Name * </label>
            <input class="form-control" type="text"
               name="pax[<?php echo $i; ?>][FirstName]"
               placeholder="First Name" value  =  "<?php echo  $paxData['FirstName']; ?>">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Last Name * </label>
            <input class="form-control" type="text"
               name="pax[<?php echo $i; ?>][LastName]"
               placeholder="Last Name"  value  =  "<?php echo  $paxData['LastName']; ?>">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>PNR *</label>
            <input class="form-control" type="text"
               placeholder="PNR" name="pax[<?php echo $i; ?>][PNR]" value  =  "<?php echo  $PNR; ?>">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Ticket Number *</label>
            <input class="form-control" type="text"
               placeholder="Ticket Number"   name="pax[<?php echo $i; ?>][TicketNumber]" value  =  "<?php echo  $paxData['TicketNumber']; ?>">
         </div>
      </div>
      <div class="col-md-3">
         <br/>
         <div class="form-group">
            <label>  <input class="" type="checkbox"
               placeholder=""    pax-key  =  "<?php echo $i; ?>" tts-flight-ticket-upload-showpax-info =  "true"> Add Passport/D.O.B Details
            </label>
         </div>
      </div>
      <div  class  =  "col-md-12 hide issuer_remark mt-3" showpaxinfo_<?php echo $i; ?> =  "true">
         <div class  = "row">
            <div class="col-md-2">
               <div class="form-group">
                  <label>D.O.B </label>
                  <input class="form-control" type="text"
                     name="pax[<?php echo $i; ?>][DateOfBirth]"
                     placeholder="Date of Birth" dob-calendor =  "true"  value  =  "<?php echo  $paxData['DateOfBirth']!=NULL?date("d M Y",strtotime(explode("T",$paxData['DateOfBirth'])[0])):""; ?>">
               </div>
            </div>
            <div class="col-md-2">
               <div class="form-group">
                  <label>PAN </label>
                  <input class="form-control" type="text"
                     name="pax[<?php echo $i; ?>][PAN]"
                     placeholder="Pan Number" value  =  "<?php echo  $paxData['PAN']; ?>">
               </div>
            </div>
            <div class="col-md-2 pr0">
               <div class="form-group">
                  <label> Passport Nationality </label>
                  <select class="form-control"  name="pax[<?php echo $i; ?>][Nationality]">
                     <option value="">Select </option>
                     <?php $country_codes = get_countary_code();
                        if ($country_codes) {
                            foreach ($country_codes as $country_code) { ?>
                     <option value="<?php echo $country_code['countrycode']; ?>"  <?php echo isset($paxData['Nationality'])&& $country_code['countrycode'] == $paxData['Nationality']?"selected":"";  ?>>
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
                     name="pax[<?php echo $i; ?>][PassportNo]"
                     placeholder="Passport Number"  value  =  "<?php echo  $paxData['PassportNo']; ?>">
               </div>
            </div>
            <div class="col-md-2">
               <div class="form-group">
                  <label>Passport Issue Date </label>
                  <input class="form-control" type="text" flight_pass_expiry="true"
                     name="pax[<?php echo $i; ?>][PassportIssue]"
                     placeholder="Passport Issue Date"  dob-calendor =  "true" value  =  "<?php echo  isset($paxData['PassportIssue'])?$paxData['PassportIssue']:""; ?>">
               </div>
            </div>
            <div class="col-md-2">
               <div class="form-group">
                  <label>Passport Expiry Date</label>
                  <input class="form-control" type="text" flight_pass_expiry="true"
                     name="pax[<?php echo $i; ?>][PassportExpiry]"
                     placeholder="Passport Expiry Date"  dob-calendor =  "true"    value  =  "<?php echo  date("d M Y",strtotime(explode("T",$paxData['PassportExpiry'])[0])); ?>">
               </div>
            </div>
            <div class="col-md-2 hide" >
               <div class="form-group">
                  <label>Ticket Id </label>
                  <input class="form-control" type="text"
                     placeholder="Ticket Id"   name="pax[<?php echo $i; ?>][TicketId]" value  =  "<?php   echo  isset($paxData['TicketId'])?$paxData['TicketId']:"";  ?>">
               </div>
            </div>
            <div class="col-md-2 hide">
               <div class="form-group">
                  <label>Passenger Type *</label>
                  <select class="form-control" name="pax[<?php echo $i; ?>][PaxType]">
                     <option value="" selected>Select</option>
                     <option value="Adult" <?php echo  $paxData['PaxType']=="1"?"selected":""; ?>>Adult</option>
                     <option value="Child" <?php echo  $paxData['PaxType']=="2"?"selected":""; ?>>Children</option>
                     <option value="Infant" <?php echo  $paxData['PaxType']=="3"?"selected":""; ?>>Infant</option>
                  </select>
               </div>
            </div>
            <div class="col-md-1 hide">
               <div class="form-group">
                  <label>Gender *</label>
                  <select class="form-control" name="pax[<?php echo $i; ?>][Gender]">
                     <option value="" selected>Select</option>
                     <option value="Male" <?php echo  $paxData['Gender']=="1"?"selected":""; ?>>Male</option>
                     <option value="Female" <?php echo  $paxData['Gender']=="2"?"selected":""; ?>>Female</option>
                  </select>
               </div>
            </div>
         </div>
      </div>
      <?php if(0) { ?>
      <div class="col-md-1">
         <br/>
         <div class="form-group">
            <label>  <input class="" type="checkbox"
               placeholder="Meal/Baggage"   name="pax[<?php echo $i; ?>][meal_bggage]"   pax-key  =  "<?php echo $i; ?>" tts-flight-ticket-upload-meal-seclect =  "true"> Meal/Baggage
            </label>
         </div>
      </div>
      <?php } ?>
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
            <samp class="badge badge-md badge-primary"><?php echo   $SegmentCities; ?></samp>
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
<?php  } }   ?>