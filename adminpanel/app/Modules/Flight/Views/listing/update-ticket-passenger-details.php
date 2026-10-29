<?php  $travllerInfo  =  json_decode($bookingInfo['travelersInfo'],true);?>
<div class="tts-d-content">
   <?php if($travllerInfo) { foreach($travllerInfo as $paxkey=>$travelers) {  $i  =   $travelers['id'];?>
   <div class="tts-d-content tts-passenger-row row">
      <div class="col-md-12">
         <h6>
            <span class="tts-text-success">Passenger <?php echo  ($paxkey+1); ?> </span>
         </h6>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Title *</label>
            <select class="form-select" disabled>
               <option value="" selected>Select Title</option>
               <option value="Mr" <?php echo  $travelers['title']=="Mr"?"selected":"";?>>Mr</option>
               <option value="Ms" <?php echo  $travelers['title']=="Ms"?"selected":"";?>>Ms</option>
               <option value="Mrs" <?php echo  $travelers['title']=="Mrs"?"selected":"";?>>Mrs</option>
               <option value="Ms" <?php echo  $travelers['title']=="Ms"?"selected":"";?>>Ms</option>
               <option value="Mstr" <?php echo  $travelers['title']=="Mstr"?"selected":"";?>>Mstr</option>
            </select>
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>First Name * </label>
            <input class="form-control" type="text"
               placeholder="First Name" value  =  "<?php echo $travelers['first_name'];?>" disabled>
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Last Name * </label>
            <input class="form-control" type="text"
               placeholder="Last Name" value  =  "<?php echo $travelers['last_name'];?>" disabled>
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Passenger Type *</label>
            <select class="form-select" disabled>
               <option value="" selected>Select Passenger Type</option>
               <option value="Adult" <?php echo  $travelers['pax_type']=="Adult"?"selected":"";?> >Adult</option>
               <option value="Child" <?php echo  $travelers['pax_type']=="Child"?"selected":"";?>>Children</option>
               <option value="Infant" <?php echo  $travelers['pax_type']=="Infant"?"selected":"";?> >Infant</option>
            </select>
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Pax Booking Status *</label>
            <select class="form-select" name="pax[<?php echo $i; ?>][booking_status]" placeholder="Booking Status">
               <option value="Confirmed" <?php echo  $travelers['booking_status']=="Confirmed"?"selected":"";?>>Confirmed</option>
               <option value="Cancelled" <?php echo  $travelers['booking_status']=="Cancelled"?"selected":"";?>>Cancelled</option>
               <option value="Processing" <?php echo  $travelers['booking_status']=="Processing"?"selected":"";?>>Processing</option>
               <option value="Hold" <?php echo  $travelers['booking_status']=="Hold"?"selected":"";?>>Hold</option>
               <option value="Failed" <?php echo  $travelers['booking_status']=="Failed"?"selected":"";?>>Failed</option>
            </select>
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>PNR *</label>
            <input class="form-control" type="text"
               placeholder="PNR" name="pax[<?php echo $i; ?>][pnr]" value  =  "<?php echo $bookingInfo['pnr'];?>">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Ticket Number *</label>
            <input class="form-control" type="text"
               placeholder="Ticket Number"   name="pax[<?php echo $i; ?>][ticket_number]"  value  =  "<?php echo $travelers['ticket_number'];?>">
         </div>
      </div>
      <div class="col-md-2">
         <div class="form-group">
            <label>Ticket Id</label>
            <input class="form-control" type="text"
               placeholder="Ticket Number"   name="pax[<?php echo $i; ?>][ticket_id]"  value  =  "<?php echo $travelers['ticket_id'];?>">
         </div>
      </div>
   </div>
   <?php }} ?>
</div>