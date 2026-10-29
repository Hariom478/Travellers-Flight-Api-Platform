
<div class="page-content">
   <div class="table_title">
      <section class="cart_information p-0">
         <div class="container-fluid p-0">
            <div class="sale_bar">
               <div class="row align-items-center">
                  <div class="col-md-4">
                     <h5 class="m-0"> Amendment  Details (<?php echo $bookingDetail['booking_ref_number']; ?>)</h5>
                  </div>
                  <div class="col-md-8 text-md-end">
                     <a class="badge badge-wt" href="<?php echo site_url('/flight/confirmation/') . $ticketData = dev_encode(json_encode(array($bookingDetail['id']))); ?>" target  =  "_blank">Booking Summary</a>
                  </div>
               </div>
            </div>
            <form action="<?php echo site_url('flight/raise-amendment-type'); ?>" method="post"  tts-form="true" name="flight-raise-amendment-type">
               <div class="row">
                  <div class="col-md-12 col-12 col-lg-12">
                     <div class="cart_info">
                        
                           <div class="cart-details-borderline">
                              <div class="row">
                                 <div class="col-md-4 col-xs-6 col-6">
                                    <div class="cart_info-field">
                                       <p class="cart_info-field--title m-0">Booking Ref Number :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $bookingDetail['booking_ref_number']; ?></span></span>
                                       </p>
                                    </div>
                                 </div>
                                 <div class="col-md-4 col-xs-6 col-6">
                                    <div class="cart_info-field">
                                       <p class="cart_info-field--title m-0">Amendment Type :<span class="cart_info-field--detail"><span> &nbsp;<?php echo ucfirst(str_replace("_"," ",$requestData['amendment_type'])); ?></span></span>
                                       </p>
                                    </div>
                                 </div>
                                 <?php if ($bookingDetail['pnr'] != "") { ?>
                                 <div class="col-md-4 col-xs-6 col-6">
                                    <div class="cart_info-field">
                                       <p class="cart_info-field--title m-0">PNR :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $bookingDetail['pnr']; ?></span></span>
                                       </p>
                                    </div>
                                 </div>
                                 <?php } ?>
                              </div>
                           </div>
                        
                       
                           <div class="cart-details-borderline">
                              <?php if (isset($bookingDetail['segments'])) {
                                 $tripInfo = json_decode($bookingDetail['segments'], true);
                                 foreach ($tripInfo as $key => $trips) {
                                 	if ($trips) { 
                                 		$firstSegment  =  reset($trips);
                                 		$lastegment  =  end($trips);
                                 		?>
                              <div class="row segment_header-top">
                                 <div class  =  "col-md-12"> <span class=""><?php echo $firstSegment['Origin']['CityName']  ?></span>-><span><?php echo $lastegment['Destination']['CityName']  ?></span><span class="margin-left">on <?php echo get_flight_date($firstSegment['Origin']['DepartTime']); ?></span></div>
                              </div>
                              <?php 	foreach ($trips as $segmentIndicatorkey => $segment) { ?>
                              <div class="row segment_body m-0">
                                 <div class="col-md-2">
                                    <div class="segment_body-airlogo">
                                       <span class="airline-logo <?php echo $bookingDetail['airlineLogoClass']; ?> size-28 x<?php echo $segment['Airline']['AirlineCode']; ?>"></span>
                                       <p class="mb-0"><?php echo $segment['Airline']['AirlineName']; ?>
                                          <span class="airline-code"><?php echo $segment['Airline']['AirlineCode']; ?> -<?php echo $segment['Airline']['FlightNumber']; ?></span>
                                       </p>
                                    </div>
                                 </div>
                                 <div class="col-md-4 segment_body-flight-info text-center">
                                    <p class="mb-0"><?php echo $segment['Origin']['CityName']; ?>
                                       <span class="air_sourcr-none"><?php echo $segment['Origin']['CountryName']; ?> (<?php echo $segment['Origin']['AirportName']; ?>) - <?php echo $segment['Origin']['CityCode']; ?></span>
                                    </p>
                                    <p class="mb-0"><?php echo get_flight_date($segment['Origin']['DepartTime']); ?>, <?php echo get_flight_time($segment['Origin']['DepartTime']); ?></p>
                                 </div>
                                 <div class="col-md-2 segment_body-flight-stop text-center">
                                    <span class="via-city-codes">Non-Stop</span>
                                    <div class="arrow_right-sm"></div>
                                 </div>
                                 <div class="col-md-4 segment_body-flight-info text-center">
                                    <p class="mb-0"><?php echo $segment['Destination']['CityName']; ?>
                                       <span class="air_sourcr-none"><?php echo $segment['Destination']['CountryName']; ?> (<?php echo $segment['Destination']['AirportName']; ?>) - <?php echo $segment['Destination']['CityCode']; ?></span>
                                    </p>
                                    <p class="mb-0"><?php echo get_flight_date($segment['Destination']['ArrivalTime']); ?>, <?php echo get_flight_time($segment['Destination']['ArrivalTime']); ?></p>
                                 </div>
                              </div>
                              <?php }
                                 }
                                 }
                                 } ?>
                           </div>
                        
                        <?php  if (isset($bookingDetail['travelersInfo'])) {
                           $travelersInfo = json_decode($bookingDetail['travelersInfo'], true);
                           ?>
                       
                           <div class="passenger_list">
                              <div class="row">
                                 <input type="hidden"  class="al-checkfield" name="booking_ref_number" value  =  "<?php echo  $bookingDetail['booking_ref_number'] ?>">
                                 <input type="hidden"  class="al-checkfield" name="amendment_type" value  =  "<?php echo  $requestData['amendment_type'] ?>">
                                 <?php  
                                    foreach ($travelersInfo as $paxkey => $traveler) { ?>
                                 <div class="col-md-4 col-xs-6 col-6">
                                    <div class="passenger_list-details cart-details-borderline">
                                       <div class="passenger_list-details-fix-box">
                                          <label class="al-label">
                                          <?php if($traveler['booking_status']!="Cancelled" || $traveler['booking_status']!="Processing") { ?>
                                          <input type="checkbox"  class="al-checkfield" name="passengers[]" value  =  "<?php echo  dev_encode($traveler['id']); ?>"      <?php if($requestData['amendment_type']=="full_refund") { echo  "checked readonly"; } ?> data-validation  =  "required"  data-validation-error-msg-required="Please select passenger" data-validation-qty ="1">
                                          <?php } ?>
                                          <span class="pax-name no_margin"> <?php echo $traveler['title']." ".$traveler['first_name']." ".$traveler['last_name']; ?>  (<?php echo $traveler['pax_type'] ?>)<span class="tooltip-keys"></span></span>
                                          </label>
                                       </div>
                                    </div>
                                 </div>
                                 <?php  } ?>
                              </div>
                           </div>
                       
                        
                           <div class ="row">
                              <div class  =  "col-md-12">
                                 <label class="col-form-label">Remark</label>
                                 <textarea class  =  "form-control"  name  =  "remark" data-validation  =  "required"  data-validation-error-msg-required="Please enter remark"></textarea>
                              </div>
                              <div class  =  "col-md-12 mt-3">
                                 <button type  =  "submit" class  =  "btn btn-primary">Submit</button>
                              </div>
                           </div>
                        
                        <?php }  ?>
                        <?php if($requestData['amendment_type'] == "cancellation" || $requestData['amendment_type'] == "cancellation_quotation" ) 
               { ?>
            <div class="imp_amdement_noti mt-3">
               <div class="col-md-12 travelimp__thanku--panelHeadwrap">
                  <div class="travelimp__thanku--panelHeading ">Important Information</div>
                  <ul class="travelimp__termswrap--termsul">
                     <li class="travelimp__termswrap--termslist">1. Cancellation permitted 06 Hrs before scheduled departure.</li>
                     <li class="travelimp__termswrap--termslist">2. In case of Normal Cancellation penalty will be levied and Balance amount will be refunded to portal wallet Cancellation permitted 06 Hrs before scheduled departure.</li>
                     <li class="travelimp__termswrap--termslist">3. Partial Refund will be processed offline.</li>
                     <li class="travelimp__termswrap--termslist">4. In case of Infant booking, cancellation will be processed offline.</li>
                     <li class="travelimp__termswrap--termslist">5. In case of One sector to be cancel, please send the offline request.</li>
                     <li class="travelimp__termswrap--termslist">6. In case of Flight cancellation/ flight reschedule, please select flight cancelled.</li>
                     <li class="travelimp__termswrap--termslist">7. Cancellation Charges cannot be retrieved for Partial Cancelled Booking.</li>
                     <li class="travelimp__termswrap--termslist">8. *Refund will take minimum 24-72 hour after cancellation.</li>
                     <li class="travelimp__termswrap--termslist">9. *if any Dispute or No show refund so it may takes more than 7 days.</li>
                  </ul>
               </div>
            </div>
            <?php }  ?>
                     </div>
                  </div>
               </div>
            </form>
            
         </div>
      </section>
   </div>
</div>