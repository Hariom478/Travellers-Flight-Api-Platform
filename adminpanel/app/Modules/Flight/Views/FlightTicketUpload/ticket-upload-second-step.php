<div class="content">
   <div class="page-content">
      <div class="page-content-area">
         <div class="card-header">
            <div class="table_title">
               <div class="topbar">
                  <h5 class="m0"> <?php $i = 0;
                     echo $title ?> </h5>
               </div>
            </div>
         </div>
         <div class="card-body">
            <form name="web-partner" tts-form='true'
               action="<?php echo site_url('flight-ticket-upload/save-passenger'); ?>"
               method="POST" id="flight-upload-ticket">
               <div class="table_title">
                  <div class="view_head">
                     <div class="row align-items-center">
                        <div class="col-md-2">
                           <span>Passenger Details</span>
                        </div>
                        <div class="col-md-10 text-end">
                           <button type="button" class="badge badge-wt" flight-ticket-upload-add-passenger-harish="true"
                              tts-passenger-method-name="flight-ticket-upload/passenger-details" passenger-counter =  "<?php echo  $passengerCounter?>" pax-type  =  "Adult" passenger-counter-Adult =  "0"><i
                              class="fa-solid fa-add "></i> Add Adult
                           </button>
                           <button type="button" class="badge badge-wt" flight-ticket-upload-add-passenger-harish="true"
                              tts-passenger-method-name="flight-ticket-upload/passenger-details" passenger-counter =  "<?php echo  $passengerCounter?>"  pax-type  =  "Child"  passenger-counter-Child =  "<?php echo  $passengerChild?>"><i><i
                              class="fa-solid fa-add "></i> Add Child
                           </button>
                           <button type="button" class="badge badge-wt" flight-ticket-upload-add-passenger-harish="true"
                              tts-passenger-method-name="flight-ticket-upload/passenger-details" passenger-counter =  "<?php echo  $passengerCounter?>"  pax-type  =  "Infant" passenger-counter-Infant =  "<?php echo  $passengerInfant?>"><i
                              class="fa-solid fa-add"></i> Add Infant
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
               <input type  =  "hidden"  name  =  "temptripSegmentId"  value  =  "<?php echo  $SegmentInfokey; ?>">
               <div class="row" tts-call-put-passenger-Adult-html="true">
                  <?php echo  $passengerDetailinfoView; ?>
               </div>
               <div class="row" tts-call-put-passenger-Child-html="true">
               </div>
               <div class="row" tts-call-put-passenger-Infant-html="true">
               </div>
               <div class="row" tts-call-put-passenger-Adult-pricing-html="true">
                  <?php if(isset($paxInfoPricingData) &&  $paxInfoPricingData!=""){  $pricingInfo['pricingInfo'] =  $paxInfoPricingData['Adult']; echo  view('Modules\Flight\Views\FlightTicketUpload\ticket-upload-pax-pricing', $pricingInfo); } else{  echo  $passengerPricingView;} ?>
               </div>
               <div class="row" tts-call-put-passenger-Child-pricing-html="true" Child-pricing-status  =  "<?php if(isset($paxInfoPricingData) &&  $paxInfoPricingData!=""  &&  isset($paxInfoPricingData['Child'])){ echo  "yes"; }  else { echo  $ChildPricingPaxShow;}?>">
                  <?php if(isset($paxInfoPricingData) &&  $paxInfoPricingData!=""  &&  isset($paxInfoPricingData['Child'])){  $pricingInfo['pricingInfo'] =  $paxInfoPricingData['Child']; $pricingInfo['pax_type'] =  "Child"; echo  view('Modules\Flight\Views\FlightTicketUpload\ticket-upload-pax-pricing', $pricingInfo); } ?>
               </div>
               <div class="row" tts-call-put-passenger-Infant-pricing-html="true" Infant-pricing-status  =  "<?php if(isset($paxInfoPricingData) &&  $paxInfoPricingData!=""  &&  isset($paxInfoPricingData['Infant'])){ echo  "yes"; }  else { echo  $InfantPricingPaxShow;}?>">
                  <?php if(isset($paxInfoPricingData) &&  $paxInfoPricingData!="" &&  isset($paxInfoPricingData['Infant'])){  $pricingInfo['pricingInfo'] =  $paxInfoPricingData['Infant']; $pricingInfo['pax_type'] =  "Infant";echo  view('Modules\Flight\Views\FlightTicketUpload\ticket-upload-pax-pricing', $pricingInfo); } ?>
               </div>
               <div class="row">
                  <div class="col-md-12 ">
                     <h6 class="view_head">Deal & Markup </h6>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <label>Basic * </label>
                        <input class="form-control" type="text"
                           name="deal[basic]" 
                           placeholder="Base " value  =  "<?php echo   isset($dealData['basic'])?$dealData['basic']:"0"; ?>" >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <label>YQ *</label>
                        <input class="form-control" type="text"
                           name="deal[yq]"    
                           placeholder="YQ" value="<?php echo   isset($dealData['yq'])?$dealData['yq']:0; ?>">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <label>Basic IATA *</label>
                        <input class="form-control" type="text"
                           name="deal[basic_iata]"
                           placeholder="Basic IATA" value  =  "<?php echo   isset($dealData['basic_iata'])?$dealData['basic_iata']:0; ?>">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <label>YQ IATA *</label>
                        <input class="form-control" type="text"
                           name="deal[yq_iata]"
                           placeholder="YQ IATA" value  =  "<?php echo   isset($dealData['yq_iata'])?$dealData['yq_iata']:0; ?>">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <label>Display Markup*</label>
                        <select class="form-select" name="deal[display_markup]" placeholder="Display Markup">
                           <option value="in_tax" <?php echo   isset($dealData['display_markup'])&& $dealData['display_markup']=='in_tax'?"selected":''; ?>  >In Tax</option>
                           <option value="in_service_charge" <?php echo   isset($dealData['display_markup'])&& $dealData['display_markup']=='in_service_charge'?"selected":''; ?>>In Service Charge</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <label>Markup (Per pax)*</label>
                        <input class="form-control" type="text"
                           name="deal[markup]"
                           placeholder="Markup (Per pax)" value  =  "<?php echo   isset($dealData['markup'])?$dealData['markup']:0; ?>">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <a class="btn btn-primary" href  =  "<?php echo  site_url('flight-ticket-upload?segmentinfokey='.$SegmentInfokey) ?>"> Previous</a>
                  </div>
                  <div class="col-md-6 text-end">
                     <input class="btn btn-primary" type="submit" value="Review Details">
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>