<style>

.segment_body {
    display: flex;
    display: -webkit-flex;
    display: -moz-flex;
    align-items: center;
    -webkit-align-items: center;
    -moz-align-items: center;
    padding: 10px;
    background-color: #f4f7f8;
    width: 100%;
    flex-wrap: wrap;
    box-shadow: 0 1px 4px 0 rgb(0 0 0 / 14%);
    border-radius: 6px;
    margin-bottom: 5px;
}
.segment_header-top {
    background: #fff;
    color: #004684;
    padding: 12px 10px;
    display: flex;
    display: -webkit-flex;
    display: -moz-flex;
    align-items: center;
    -webkit-align-items: center;
    -moz-align-items: center;
    font-size: 13px;
    font-weight: 500;
    border-radius: 6px 6px 0 0;
}
.passenger_list div {
    padding-right: 0;
}
input[type="checkbox"][readonly] {
  pointer-events: none;
}
.passenger_list-details {
    display: flex;
    flex-direction: column;
    display: -webkit-flex;
    display: -moz-flex;
    align-items: center;
    -webkit-align-items: center;
    -moz-align-items: center;
    padding-top: 5px;
    background: #fff;
    box-shadow: 0 1px 4px 0 rgb(0 0 0 / 14%);
    border-radius: 6px;
    margin: 8px 0;
    min-height: 60px;
}


.al-label:hover .al-indiv {
    background: #fff;
}


.passenger_list-details-erormsg .al-label .al-indiv {
    width: 16px;
    height: 16px;
    line-height: 15px;
}
.al-label i {
    font-size: 14px;
    opacity: 0;
}
.fa-check:before {
    content: "\f00c";
}
/* .arrow_right-sm {
    height: 2px;
    width: 3%;
    background: #004684;
    margin: 0 10px;
    position: relative;
}
.arrow_right-sm:before {
    content: '';
    position: absolute;
    height: 8px;
    top: -6px;
    width: 2px;
    background: #004684;
    right: 3px;
    transform: rotate(135deg);
}
.arrow_right-sm:after {
    content: '';
    position: absolute;
    height: 8px;
    top: 0;
    width: 2px;
    background: #004684;
    right: 3px;
    transform: rotate(45deg);
} */
.segment_header-top .margin-left {
    margin-left: 0.3%;
}

</style>
<div class="page-content">
	<div class="table_title">

		<section class="cart_information">
			<div class="container">
				<div class="sale_bar">
					<div class="tts_row">
						<div class="tts-col-6">
							<h3> Amendment  Details (<?php echo $bookingDetail['booking_ref_number']; ?>)</h3>
						</div>
						<div class="tts-col-6 text_right">
							<a class="btn btn-sm btn-info text-white" href="<?php echo site_url('/flight/confirmation/') . $ticketData = dev_encode(json_encode(array($bookingDetail['id']))); ?>" target  =  "_blank">Booking Summary</a>
						</div>
					</div>
				</div>
				<form action="<?php echo site_url('flight/raise-amendment-type'); ?>" method="post"  tts-form="true" name="flight-raise-amendment-type">
                 
				<div class="row">
					<div class="col-md-12 col-12 col-lg-12">
						<div class="cart_info">
									<div class  =  "sale_bar">
										<div class="cart-details-borderline">
											<div class="row">
												<div class="row">
													<div class="col-md-4 col-xs-6">
														<div class="cart_info-field">
															<p class="cart_info-field--title">Booking Ref Number :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $bookingDetail['booking_ref_number']; ?></span></span>
															</p>
														</div>
													</div>
													<div class="col-md-4 col-xs-6">
														<div class="cart_info-field">
															<p class="cart_info-field--title">Amendment Type :<span class="cart_info-field--detail"><span> &nbsp;<?php echo ucfirst(str_replace("_"," ",$requestData['amendment_type'])); ?></span></span>
															</p>
														</div>
													</div>
													<?php if ($bookingDetail['pnr'] != "") { ?>
														<div class="col-md-4 col-xs-6">
															<div class="cart_info-field">
																<p class="cart_info-field--title">PNR :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $bookingDetail['pnr']; ?></span></span>
																</p>
															</div>
														</div>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
									<div  class  =  "sale_bar">
										<div class="cart-details-borderline">
											<?php if (isset($bookingDetail['segments'])) {
												$tripInfo = json_decode($bookingDetail['segments'], true);
												foreach ($tripInfo as $key => $trips) {
													if ($trips) { 
														$firstSegment  =  reset($trips);
														$lastegment  =  end($trips);
														?>
                                                      <div class="row segment_header-top"><div class  =  "col-md-12"> <span class=""><?php echo $firstSegment['Origin']['CityName']  ?></span>-><span><?php echo $lastegment['Destination']['CityName']  ?></span><span class="margin-left">on <?php echo get_flight_date($firstSegment['Origin']['DepartTime']); ?></span></div></div>
													<?php 	foreach ($trips as $segmentIndicatorkey => $segment) { ?>
															<div class="row segment_body">
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
									</div>
									<?php  if (isset($bookingDetail['travelersInfo'])) {
    $travelersInfo = json_decode($bookingDetail['travelersInfo'], true);
    ?>
									<div  class  =  "sale_bar">
									<div class="passenger_list">
   <div class="row">
   <input type="hidden"  class="al-checkfield" name="booking_ref_number" value  =  "<?php echo  $bookingDetail['booking_ref_number'] ?>">
               <input type="hidden"  class="al-checkfield" name="amendment_type" value  =  "<?php echo  $requestData['amendment_type'] ?>">
	<?php  
	
	
	foreach ($travelersInfo as $paxkey => $traveler) { ?>
      <div class="col-md-4 col-xs-6">
         <div class="passenger_list-details">
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
						</div>
						<div class  =  "sale_bar">
						<div class =  "row">
<div class  =  "col-md-12">
<label class="col-form-label">Remark</label>
	<textarea class  =  "form-control"  name  =  "remark" data-validation  =  "required"  data-validation-error-msg-required="Please enter remark"></textarea>
</div>	
<div class  =  "col-md-12 mt-1">
<button type  =  "submit" class  =  "btn btn-success">Submit</button>
</div>	

</div>
						</div>
						<?php }  ?>
					</div>
				</div>
			</div>
	</form>


	<?php if($requestData['amendment_type'] == "cancellation" || $requestData['amendment_type'] == "cancellation_quotation" ) 
	{ ?>
		<div class="imp_amdement_noti">
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
		</section>
	</div>
</div>