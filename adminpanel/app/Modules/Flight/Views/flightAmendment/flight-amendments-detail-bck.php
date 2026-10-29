<div class="page-content">
	<div class="table_title">
	<style>

.input-floating-label {
    font-size: 13px;
    padding: 13.5px 0;
    border: none;
    border-bottom: solid 1px #e5e5e5;
    width: 100%;
    box-sizing: border-box;
    transition: all 0.3s linear;
    color: #333;
    font-weight: 400;
    -webkit-appearance: none;
    -moz-appearance: none;
    -o-appearance: none;
    border-radius: 0;
    background: transparent;
}
	</style>
<section class="cart_information p-0">
	<div class="container-fluid p-0"> 
	<div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <h5 class="m-0"> Flight Amendment Details (<?php echo  $amendmentDetail['id']; ?>)</h5>
               </div>
               <div class="col-md-8 text-md-end">
			   <a class  =  "badge badge-wt" href="<?php echo site_url('/flight/confirmation/') .   $ticketData  =  dev_encode(json_encode(array($amendmentDetail['flightBookingid']))); ?>">Booking Summary</a>
               </div>
            </div>
         </div>
		<div class="row">
			<div class="col-md-12 col-12 col-lg-12">
				<div class="cart_info">
					<div class="accordion" id="accordionExample">
						  <div class="accordion-item">
						  	<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseamendment" aria-expanded="true" aria-controls="collapseamendment">
						      <span class="acordian_heading">Amendment Information : <?php echo  $amendmentDetail['id'];?></span>
						     </button>
						    <div id="collapseamendment" class="accordion-collapse collapse show" aria-labelledby="headingamendment" data-bs-parent="#accordionExample">
						      <div class="accordion-body cart-details-borderline">
						       	<div class="row">
						       		<div class="row">
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">Amendment Id :<span class="cart_info-field--detail"><span> &nbsp;<?php echo  $amendmentDetail['id'];?></span></span></p>
									      </div>
									   </div>
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">Amendment Status :<span class="cart_info-field--detail"><span> &nbsp;&nbsp;<?php echo  ucfirst($amendmentDetail['amendment_status']);?></span></span></p>
									      </div>
									   </div>
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">Amendment Type :<span class="cart_info-field--detail"><span> &nbsp;<?php echo ucfirst($amendmentDetail['amendment_type']);?></span></span></p>
									      </div>
									   </div>
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">Remark From Agent:<span class="cart_info-field--detail"><span> &nbsp;<?php echo  $amendmentDetail['remark_from_web_partner'];?></span></span></p>
									      </div>
									   </div>
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">Remark From Company :<span class="cart_info-field--detail"><span> &nbsp;<?php echo  $amendmentDetail['remark_from_super_admin'];?></span></span></p>
									      </div>
									   </div>
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">CreatedOn :<span class="cart_info-field--detail"><span> &nbsp;<?php echo date_created_format($amendmentDetail['created']); ?></span></span></p>
									      </div>
									   </div>
									</div>
						       	</div>
						      </div>
						    </div>
						  </div>
						  <div class="accordion-item">
						  	<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseamendmentDetail" aria-expanded="true" aria-controls="collapseamendmentDetail">
						      <span class="acordian_heading">Amendment Detail </span>
						     </button>
						    <div id="collapseamendmentDetail" class="accordion-collapse collapse show" aria-labelledby="headingamendmentDetail" data-bs-parent="#accordionExample">
							<?php $amendmentrequestDetail = json_decode($amendmentDetail['request'],true); ?>	
							<div class="accordion-body cart-details-borderline">
								<?php if(isset($amendmentrequestDetail['Sectors'])) {   ?>
						       		<div class="row">
									   <?php  foreach ($amendmentrequestDetail['Sectors'] as  $Sectors) { ?>
									   <div class="col-md-1">
										   	<div class="segment_body-airlogo">
										      <p class="mb-0"><span class="airline-code"><?php echo  $Sectors['Origin']; ?> -<?php echo  $Sectors['Destination']; ?></span></p>
										   </div>
									   </div>
									   <?php } ?>
									</div>
									<?php }   ?>
									<div class="amend_details-passengers--list">
									<form action="<?php echo site_url('flight/flight-amendment-cancellation-charge'); ?>" method="post" tts-form="true" name="cancellation_charge_update">
										<?php if(isset($amendmentDetail['travelersInfo'])) { $travelersInfo  =  $amendmentDetail['travelersInfo'];  $cancelledpaxInfoId  =  explode(",",$amendmentDetail['pax_id']); $amendmentPassengerKey=0;foreach($travelersInfo as $paxkey=>$traveler){ 
											$amendment_charges =  array();
											$charge =   0;
											$service_charge =   0;
											$meal_charge =   0;
											$baggage_charge =  0;
											$seat_charge =   0;
											$refund =   0;
											$service_charge_gst =   0;
											if($traveler['amendment_charges']!=Null){
											$amendment_charges  =  json_decode($traveler['amendment_charges'],true);
											$charge =   $amendment_charges['Charge'];
											$service_charge =   $amendment_charges['ServiceCharge'];
											$meal_charge =   $amendment_charges['MealCharge'];
											$baggage_charge =   $amendment_charges['BaggageCharge'];
											$seat_charge =   $amendment_charges['SeatCharge'];
											$refund =   $amendment_charges['Refund'];
											$service_charge_gst =   $amendment_charges['GST']['TotalGSTAmount'];
											}
											$fare  =  json_decode($traveler['fare'],true);
											if(in_array($traveler['id'],$cancelledpaxInfoId)){
											?>

										
										<div class="row">
											<div class="col-md-4">
												<div class="amend_passenger_details">
													<span>Last Name/First Name Title</span>
													<div class="person-name d-flex align-items-center justify-content-between">
														<span class=""><?php echo $paxkey+1; ?>. <?php echo  $traveler['last_name'];?>/<?php echo  $traveler['first_name'];?> <?php echo  $traveler['title'];?>. (<?php echo  $traveler['pax_type'];?>)</span>
													</div>
													<div class="row">
													<div class="col-md-6">
															<span class="sm_font padd-left-amendment">Status : <span class="bold"><?php echo  $traveler['booking_status'];?></span></span>
														</div>
													<?php if( $traveler['date_of_birth']!=NULL) {  ?>
														<div class="col-md-6">
															<span class="sm_font padd-left-amendment">DOB : <span class="bold"><?php echo $traveler['date_of_birth']!=""?display_custom_date_format($traveler['date_of_birth'],false):"-"; ?></span></span>
														</div>
														<?php } ?>
														<?php if( $traveler['pan_number']!=NULL) {  ?>
														<div class="col-md-6">
															<span class="sm_font padd-left-amendment">Pan Number : <span class="bold"><?php echo  $traveler['pan_number'];?></span></span>
														</div>
														<?php } ?>
														<?php if( $traveler['passport_number']!=NULL) {  ?>
														<div class="col-md-6">
															<span class="sm_font padd-left-amendment">Passport Number : <span class="bold"><?php echo  $traveler['passport_number'];?></span></span>
														</div>
														<?php } ?>
														<?php if( $traveler['passport_expiry']!=NULL) {  ?>
														<div class="col-md-6">
															<span class="sm_font padd-left-amendment">Passport Expiry : <span class="bold"><?php echo $traveler['passport_expiry']!=""?display_custom_date_format($traveler['passport_expiry'],false):"-"; ?></span></span>
														</div>
														<?php } ?>
													</div>
												</div>
											</div>
											<div class="col-sm-8 passenger_faredetail">
												<div class="row">
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													              <p class="mg_right-50">Base Fare</p>
															      <p class="price-width-left text-right"  id  =  "base_fare_<?php echo $traveler['id'];  ?>"  fareCharge  =  "<?php echo  $fare['BaseFare'];?>">₹ <?php echo  $fare['BaseFare'];      ?></p>
													</div>
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													              <p class="mg_right-50">Taxes</p>
															      <p class="price-width-left text-right" id  =  "airline_tax_<?php echo $traveler['id'];  ?>"  airlineTaxCharge  =  "<?php echo  $fare['Tax'];?>">₹ <?php echo  $fare['Tax'];      ?></p>
													</div>
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													              <p class="mg_right-50">YQ Tax</p>
															      <p class="price-width-left text-right" id  =  "yq_tax_<?php echo $traveler['id'];  ?>"  yqtaxCharge  =  "<?php echo  $fare['YQTax'];?>">₹ <?php echo  $fare['YQTax'];      ?></p>
													</div>
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													              <p class="mg_right-50">Service Charges</p>
															      <p class="price-width-left text-right"  id  =  "airline_service_charges_<?php echo $traveler['id'];  ?>"  airlineServiceCharge  =  "<?php echo  $fare['ServiceCharges'];?>">₹ <?php echo  $fare['ServiceCharges'];      ?></p>
													</div>
													
													<div class="col-sm-2 col-xs-6 padd-left-amendment <?php if( !$fare['BaggageCharges']) { echo  'hide';   } ?>" >
													              <p class="mg_right-50">Baggage Charges</p>
															      <p class="price-width-left text-right" id  =  "airline_baggage_charges_<?php echo $traveler['id'];  ?>"  airlineBaggageCharge  =  "<?php echo  $fare['BaggageCharges'];?>">₹ <?php echo  $fare['BaggageCharges'];      ?></p>
													</div>
													<div class="col-sm-2 col-xs-6 padd-left-amendment <?php if( !$fare['MealCharges']) { echo  'hide';   } ?>">
													              <p class="mg_right-50">Meal Charges</p>
															      <p class="price-width-left text-right" id  =  "airline_meal_charges_<?php echo $traveler['id'];  ?>"  airlineMealCharge  =  "<?php echo  $fare['MealCharges'];?>">₹ <?php echo  $fare['MealCharges'];      ?></p>
													</div>
													<div class="col-sm-2 col-xs-6 padd-left-amendment <?php if( !$fare['SeatCharges']) { echo  'hide';   } ?>">
													              <p class="mg_right-50">Seat Charges</p>
															      <p class="price-width-left text-right" id  =  "airline_seat_charges_<?php echo $traveler['id'];  ?>"  airlineSeatCharge  =  "<?php echo  $fare['SeatCharges'];?>">₹ <?php echo  $fare['SeatCharges'];      ?></p>
													</div>
													<?php if($amendmentDetail['pnr']!=Null) { ?>
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													<p class="mg_right-50">Airline PNR</p>
													<p class="price-width-left text-right"> <?php echo  $amendmentDetail['pnr'];      ?></p>
													</div>
													<?php } ?>
													<?php if($amendmentDetail['pnr']!=Null) { ?>
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													<p class="mg_right-50">GDS PNR</p>
													<p class="price-width-left text-right"> <?php   $gdsPnr  =  json_decode($amendmentDetail['airline_pnr'],true);  echo $gdsPnr = getGdsPnr($gdsPnr);?> </p>
													</div>
													<?php } ?>
													<?php if($traveler['ticket_number']!=Null) { ?>
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													<p class="mg_right-50">Ticket Number</p>
													<p class="price-width-left text-right"><?php echo  $traveler['ticket_number'];      ?></p>
													</div>
													<?php } ?>
													<?php if($traveler['baggage']!=Null) { ?>
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													<p class="mg_right-50">Baggage</p>
													<p class="price-width-left text-right"><?php echo  $traveler['baggage'];      ?></p>
													</div>
													<?php } ?>
													<?php if($traveler['meal']!=Null) { ?>
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													<p class="mg_right-50">Meal</p>
													<p class="price-width-left text-right"><?php echo  $traveler['meal'];      ?></p>
													</div>
													<?php  } ?>
													<input type  =  "hidden"  name  =  "amendment_id"  value  =  "<?php  echo   dev_encode( $amendmentDetail['id']);  ?>">
													<?php if($amendmentDetail['amendment_type']=="cancellation") {  $paxid  =  $traveler['id'];  ?>
													<div class="col-sm-4 col-xs-6 padd-left-amendment">
													<p class="mg_right-50">Cancellation Charge</p>
													<p class="price-width-left text-right"><input class  =  "input-floating-label"  type  =  "text"  name  =  "charge[<?php echo  $amendmentPassengerKey;?>][charge]" value  =  "<?php  echo  $charge; ?>"  id  =  "charge_<?php echo  $traveler['id'];  ?>"  oninput  =  'getFlightRefundCharges(event,"<?php echo  $paxid ;  ?>")'>
													<input  type  =  "hidden"  name  =  "charge[<?php echo  $amendmentPassengerKey;?>][pax_id]" value  =  "<?php echo $traveler['id'];  ?>" >
												</p>
													</div>
													<div class="col-sm-4 col-xs-6 padd-left-amendment">
													<p class="mg_right-50">Cancellation Service Charge</p>
													<p class="price-width-left text-right"><input class  =  "input-floating-label" type  =  "text"  name  =  "charge[<?php echo  $amendmentPassengerKey;?>][service_charge]" value  =  "<?php  echo  $service_charge; ?>" id  =  "service_charge_<?php echo  $traveler['id'];  ?>" oninput  =  'getFlightRefundCharges(event,"<?php echo  $paxid ;  ?>")'></p>
													</div>
													<div class="col-sm-4 col-xs-6 padd-left-amendment">
													<p class="mg_right-50">Meal Charge</p>
													<p class="price-width-left text-right"><input class  =  "input-floating-label" type  =  "text"  name  =  "charge[<?php echo  $amendmentPassengerKey;?>][meal_charge]" value  =  "<?php  echo  $meal_charge; ?>" id  =  "meal_charge_<?php echo  $traveler['id'];  ?>"  oninput  =  'getFlightRefundCharges(event,"<?php echo  $paxid ;  ?>")'></p>
													</div>
													<div class="col-sm-4 col-xs-6 padd-left-amendment">
													<p class="mg_right-50">Baggage Charge</p>
													<p class="price-width-left text-right"><input class  =  "input-floating-label" type  =  "text"  name  =  "charge[<?php echo  $amendmentPassengerKey;?>][baggage_charge]" value  =  "<?php  echo  $baggage_charge; ?>"  id  =  "baggage_charge_<?php echo  $traveler['id'];  ?>"  oninput  =  'getFlightRefundCharges(event,"<?php echo  $paxid ;  ?>")'></p>
													</div>
													<div class="col-sm-4 col-xs-6 padd-left-amendment">
													<p class="mg_right-50">Seat Charge</p>
													<p class="price-width-left text-right"><input class  =  "input-floating-label" type  =  "text"  name  =  "charge[<?php echo  $amendmentPassengerKey;?>][seat_charge]" value  =  "<?php  echo  $seat_charge; ?>" id  =  "seat_charge_<?php echo  $traveler['id'];  ?>"  oninput  =  'getFlightRefundCharges(event,	"<?php echo  $paxid ;  ?>")'></p>
													</div>
													<div class="col-sm-4 col-xs-6 padd-left-amendment">
													<p class="mg_right-50">Cancellation Charge GST</p>
													<p class="price-width-left text-right"><input class  =  "input-floating-label"  type  =  "text"  name  =  "charge[<?php echo  $amendmentPassengerKey;?>][service_charge_gst]" value  =  "<?php  echo  $service_charge_gst; ?>"   id  =  "service_charge_gst_<?php echo  $traveler['id'];  ?>" readonly></p>
													</div>
													<div class="col-sm-4 col-xs-6 padd-left-amendment">
													<p class="mg_right-50">Refund Amount</p>
													<p class="price-width-left text-right"><input class  =  "input-floating-label" type  =  "text"  name  =  "charge[<?php echo  $amendmentPassengerKey;?>][refund]" value  =  "<?php  echo  $refund; ?>"  id  =  "refund_<?php echo  $traveler['id'];  ?>" readonly></p>
													</div>
													<?php  } ?>
												</div>
											</div>
										</div>
										<?php $amendmentPassengerKey =  $amendmentPassengerKey+1;} } } ?>
										<?php if($amendmentDetail['refund_status']!="Close") { ?>
										<div class  =  "row"><div class  =  "col-md-12"><button class  =  "btn btn-info pull-right" type =  "submit">Update</button></div></div>
										<?php } ?>
													</form>
									</div>
						      </div>
						    </div>
						  </div>
						  <div class="accordion-item">
						  	<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
						      <span class="acordian_heading">Cart Information : <?php echo  $amendmentDetail['booking_ref_number'];?></span>
						     </button>
						    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
						      <div class="accordion-body cart-details-borderline">
						       	<div class="row">
						       		<div class="row">
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">Booking Ref Number :<span class="cart_info-field--detail"><span> &nbsp;<?php echo  $amendmentDetail['booking_ref_number'];?></span></span></p>
									      </div>
									   </div>
									   <?php if($amendmentDetail['pnr']!="") { ?>
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">PNR :<span class="cart_info-field--detail"><span> &nbsp;<?php echo  $amendmentDetail['pnr'];?></span></span></p>
									      </div>
									   </div>
									   <?php } ?>
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">Refundable :<span class="cart_info-field--detail"><span class="<?php echo $amendmentDetail['is_refundable']==1?"tts-text-success":"tts-text-danger"; ?>"> &nbsp;<?php echo $amendmentDetail['is_refundable']==1?"Yes":"NO"; ?></span></span></p>
									      </div>
									   </div>
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">Amount :<span class="cart_info-field--detail"><span> &nbsp;₹&nbsp;<?php echo  $amendmentDetail['total_price'];?></span></span></p>
									      </div>
									   </div>
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">Booking Status :<span class="cart_info-field--detail"><span> &nbsp;<?php echo  $amendmentDetail['booking_status'];?></span></span></p>
									      </div>
									   </div>
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">Payment Status :<span class="cart_info-field--detail"><span> &nbsp;<?php echo  $amendmentDetail['payment_status'];?></span></span></p>
									      </div>
									   </div>
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">Channel Type :<span class="cart_info-field--detail"><span> &nbsp;<?php echo  $amendmentDetail['booking_channel'];?></span></span></p>
									      </div>
									   </div>
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">CreatedOn :<span class="cart_info-field--detail"><span> &nbsp;<?php echo date_created_format($amendmentDetail['bookingcreated']); ?></span></span></p>
									      </div>
									   </div>
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field"><a href="<?php echo site_url('/flight/confirmation/') .   $ticketData  =  dev_encode(json_encode(array($amendmentDetail['id']))); ?>" class="">Booking Summary</a></div>
									   </div>
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">Booking User :<span class="cart_info-field--detail"><span> &nbsp;<a href="#" class=""><?php echo $amendmentDetail['staff_name']; ?></a></span></span></p>
									      </div>
									   </div>
									   <?php if($amendmentDetail['last_ticket_date']!="") { ?>
									   <div class="col-md-4 col-xs-6">
									      <div class="cart_info-field">
									         <p class="cart_info-field--title">Last Ticket Date :<span class="cart_info-field--detail"><span> &nbsp;<a href="#" class="">    <?php echo $amendmentDetail['last_ticket_date']!=""?display_custom_date_format($amendmentDetail['last_ticket_date'],true):""; ?></a></span></span></p>
									      </div>
									   </div>
									   <?php }  ?>
									</div>
						       	</div>
						      </div>
						    </div>
						  </div>
						<!--   <div class="accordion-item">
						    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
						          <span class="acordian_heading">Notes</span>
						         <span class="ball__mainwrapper"><span class="ball__border info_length-green"><span class="numbering-section">1</span></span></span>
						      </button>
						    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
						      <div class="accordion-body cart-details-borderline">
						         <div class="row">
						         	<div class="col-md-10">
						         		<div class="note_list-content">
						         			<p>inform ta on call pnr got released </p>
						         		</div>
						         	</div>
						         	<div class="col-md-2">
						         		<div class="note_list-details">
						         			<p>Aug 8, 2022 9:50 PM</p>
						         			<p>General</p>
						         			<p>Subhan Tandel (61031639)</p>
						         		</div>
						         	</div>
						         </div>
						      </div>
						    </div>
						  </div> -->
						  <!-- <div class="accordion-item">
						    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						         <span class="acordian_heading">Cart Amendments</span>
						         <span class="ball__mainwrapper"><span class="ball__border info_length-green"><span class="numbering-section">0</span></span></span>
						         <div class="cssCircle addsign"><span class="cssCircle-plusdesign">+ Raise Amendments</span></div>
						      </button>
						    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
						      <div class="accordion-body cart-details-borderline">
						       
						      </div>
						    </div>
						  </div> -->
						  <div class="accordion-item">
						    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
						         <span class="acordian_heading">Booking Details</span>
						         
						      </button>
						    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
						      <div class="accordion-body cart-details-borderline">
								<?php if(isset($amendmentDetail['segments'])) { $tripInfo  =  json_decode($amendmentDetail['segments'],true); foreach ($tripInfo  as $key =>  $trips) { if($trips){  foreach ($trips as $segmentIndicatorkey => $segment) { ?>
						       		<div class="row">
									   <div class="col-md-2">
										   	<div class="segment_body-airlogo">
											   <span class="airline-logo <?php echo  $amendmentDetail['airlineLogoClass']; ?> size-28 x<?php echo  $segment['Airline']['AirlineCode']; ?>"></span>
										      <p class="mb-0"><?php echo  $segment['Airline']['AirlineName']; ?> <span class="airline-code"><?php echo  $segment['Airline']['AirlineCode']; ?> -<?php echo  $segment['Airline']['FlightNumber']; ?></span></p>
										   </div>
									   </div>
									   <div class="col-md-4 segment_body-flight-info text-center">
									   		<p class="mb-0"><?php echo  $segment['Origin']['CityName']; ?> <span class="air_sourcr-none"><?php echo  $segment['Origin']['CountryName'];?> (<?php echo  $segment['Origin']['AirportName']; ?>) - <?php echo  $segment['Origin']['CityCode']; ?></span></p>
									   		<p class="mb-0"><?php echo  get_flight_date($segment['Origin']['DepartTime']); ?>, <?php echo  get_flight_time($segment['Origin']['DepartTime']); ?></p>
									   	</div>
									   	<div class="col-md-2 segment_body-flight-stop text-center">
									   		<span class="via-city-codes">Non-Stop</span>
									   		<div class="arrow_right-sm"></div>
									   	</div>
									   	<div class="col-md-4 segment_body-flight-info text-center">
										   <p class="mb-0"><?php echo  $segment['Destination']['CityName']; ?> <span class="air_sourcr-none"><?php echo  $segment['Destination']['CountryName'];?> (<?php echo  $segment['Destination']['AirportName']; ?>) - <?php echo  $segment['Destination']['CityCode']; ?></span></p>
									   		<p class="mb-0"><?php echo  get_flight_date($segment['Destination']['ArrivalTime']); ?>, <?php echo  get_flight_time($segment['Destination']['ArrivalTime']); ?></p>
									   	</div>
									</div>
									<?php } } } } ?>
									<div class="amend_details-passengers--list">
										<?php if(isset($amendmentDetail['travelersInfo'])) { $travelersInfo  =  $amendmentDetail['travelersInfo'];  $cancelledpaxInfoId  =  explode(",",$amendmentDetail['pax_id']); foreach($travelersInfo as $paxkey=>$traveler){ 
											$fare  =  json_decode($traveler['fare'],true);
											?>
										<div class="row">
											<div class="col-md-4">
												<div class="amend_passenger_details">
													<span>Last Name/First Name Title</span>
													<div class="person-name d-flex align-items-center justify-content-between">
														<span class=""><?php echo $paxkey+1; ?>. <?php echo  $traveler['last_name'];?>/<?php echo  $traveler['first_name'];?> <?php echo  $traveler['title'];?>. (<?php echo  $traveler['pax_type'];?>)</span>
													</div>
													<div class="row">
													<div class="col-md-6">
															<span class="sm_font padd-left-amendment">Status : <span class="bold"><?php echo  $traveler['booking_status'];?></span></span>
														</div>
													<?php if( $traveler['date_of_birth']!=NULL) {  ?>
														<div class="col-md-6">
															<span class="sm_font padd-left-amendment">DOB : <span class="bold"><?php echo $traveler['date_of_birth']!=""?display_custom_date_format($traveler['date_of_birth'],false):"-"; ?></span></span>
														</div>
														<?php } ?>
														<?php if( $traveler['pan_number']!=NULL) {  ?>
														<div class="col-md-6">
															<span class="sm_font padd-left-amendment">Pan Number : <span class="bold"><?php echo  $traveler['pan_number'];?></span></span>
														</div>
														<?php } ?>
														<?php if( $traveler['passport_number']!=NULL) {  ?>
														<div class="col-md-6">
															<span class="sm_font padd-left-amendment">Passport Number : <span class="bold"><?php echo  $traveler['passport_number'];?></span></span>
														</div>
														<?php } ?>
														<?php if( $traveler['passport_expiry']!=NULL) {  ?>
														<div class="col-md-6">
															<span class="sm_font padd-left-amendment">Passport Expiry : <span class="bold"><?php echo $traveler['passport_expiry']!=""?display_custom_date_format($traveler['passport_expiry'],false):"-"; ?></span></span>
														</div>
														<?php } ?>
													</div>
												</div>
											</div>
											<div class="col-sm-8 passenger_faredetail">
												<div class="row">
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													              <p class="mg_right-50">Base Fare</p>
															      <p class="price-width-left text-right">₹ <?php echo  $fare['BaseFare'];      ?></p>
													</div>
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													              <p class="mg_right-50">Taxes</p>
															      <p class="price-width-left text-right">₹ <?php echo  $fare['Tax'];      ?></p>
													</div>
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													              <p class="mg_right-50">YQ Tax</p>
															      <p class="price-width-left text-right">₹ <?php echo  $fare['YQTax'];      ?></p>
													</div>
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													              <p class="mg_right-50">Service Charges</p>
															      <p class="price-width-left text-right">₹ <?php echo  $fare['ServiceCharges'];      ?></p>
													</div>
													<?php if( $fare['BaggageCharges']) {  ?>
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													              <p class="mg_right-50">Baggage Charges</p>
															      <p class="price-width-left text-right">₹ <?php echo  $fare['BaggageCharges'];      ?></p>
													</div>
													<?php } ?>
													<?php if( $fare['MealCharges']) {  ?>
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													              <p class="mg_right-50">Meal Charges</p>
															      <p class="price-width-left text-right">₹ <?php echo  $fare['MealCharges'];      ?></p>
													</div>
													<?php } ?>
													<?php if( $fare['SeatCharges']) {  ?>
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													              <p class="mg_right-50">Seat Charges</p>
															      <p class="price-width-left text-right">₹ <?php echo  $fare['SeatCharges'];      ?></p>
													</div>
													<?php } ?>
													<?php if($amendmentDetail['pnr']!=Null) { ?>
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													<p class="mg_right-50">Airline PNR</p>
													<p class="price-width-left text-right"> <?php echo  $amendmentDetail['pnr'];      ?></p>
													</div>
													<?php } ?>
													<?php if($amendmentDetail['pnr']!=Null) { ?>
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													<p class="mg_right-50">GDS PNR</p>
													<p class="price-width-left text-right"><?php   $gdsPnr  =  json_decode($amendmentDetail['airline_pnr'],true);  echo $gdsPnr = getGdsPnr($gdsPnr);?> </p>
													</div>
													<?php } ?>
													<?php if($traveler['ticket_number']!=Null) { ?>
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													<p class="mg_right-50">Ticket Number</p>
													<p class="price-width-left text-right"><?php echo  $traveler['ticket_number'];      ?></p>
													</div>
													<?php } ?>
													<?php if($traveler['baggage']!=Null) { ?>
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													<p class="mg_right-50">Baggage</p>
													<p class="price-width-left text-right"><?php echo  $traveler['baggage'];      ?></p>
													</div>
													<?php } ?>
													<?php if($traveler['meal']!=Null) { ?>
													<div class="col-sm-2 col-xs-6 padd-left-amendment">
													<p class="mg_right-50">Meal</p>
													<p class="price-width-left text-right"><?php echo  $traveler['meal'];      ?></p>
													</div>
													<?php  } ?>
												</div>
											</div>
										</div>
										<?php  } } ?>
									</div>
						      </div>
						    </div>
						 </div>
						 <?php 
						   $paymentInfo  =  array();
						 if (isset($amendmentDetail['paymentInfo']) && $amendmentDetail['paymentInfo']) {
               $paymentInfo  = $amendmentDetail['paymentInfo'];
			 
						 }
               ?>
			   <?php if (!empty($paymentInfo) && is_array($paymentInfo)) { ?>
						 <div class="accordion-item">
						    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
						         <span class="acordian_heading">Payment Process</span>
						         <span class="ball__mainwrapper"><span class="ball__border info_length-green"><span class="numbering-section"><?php  echo  count($paymentInfo); ?></span></span></span>
						      </button>
						    <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
						      <div class="accordion-body cart-details-borderline">
						       		<div class="row">
						       			<div class="col-md-12">
						       				<div class="table-responsive">
						       					<table class="table table-bordered ">
												  <thead>
													<tr>
                                             <th>Ref. Number </th>
                                             <th>Remark</th>
                                             <th>Credit</th>
                                             <th>Debit</th>
                                             <th>Type</th>
                                             <th>Created</th>
                                          </tr>
												  </thead>
												  <tbody>

													<?php
                                             if (!empty($paymentInfo) && is_array($paymentInfo)) {
                                                 foreach ($paymentInfo as $data) {
                                             ?>
                                          <tr>
                                             <td> <?php echo $data['acc_ref_number']; ?></td>
                                             <td><?php echo  $data['remark']; ?></td>
                                             <td> ₹ <?php echo $data['credit']; ?></td>
                                             <td> ₹ <?php echo $data['debit']; ?></td>
                                             <td><?php echo ucfirst($data['action_type']); ?></td>
                                             <td>
                                                <?php echo date_created_format($data['created']); ?>
                                             </td>
                                          </tr>
                                          <?php }
                                             } else {
                                                 echo "<tr> <td colspan='6' class='text_center'><b>No payment Found</b></td></tr>";
                                             } ?>
												  </tbody>
												</table>
						       				</div>
						       			</div>
						       		</div>
						      </div>
						    </div>
						 </div>
						 <?php } ?>
						 <div class="accordion-item">
						    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
						         <span class="acordian_heading">Fare Breakup : </span>
						   
						      </button>
						    <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
						      <div class="accordion-body cart-details-borderline">
						      	<div class="">
								  <?php  $FareBreakUp = $amendmentDetail['FareBreakUp'];   if($FareBreakUp){ ?>
									<table style=" text-align: left;  width:100%; border-collapse: collapse; border: 1px solid #ddd;margin-bottom:20px;" >
			<tr>
			   <td style="  padding: 10px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight: bold;"><?php  echo  $FareBreakUp['WebPMarkUp']['LabelText'];?>:</td>
				 <td style="  padding: 10px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight:bold;">₹ <?php  echo  $FareBreakUp['WebPMarkUp']['Value'];?></td>
			</tr>
			<tr>
			   <td style="  padding: 10px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight: bold;"><?php  echo  $FareBreakUp['WebPDiscount']['LabelText'];?>:</td>
				 <td style="  padding: 10px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight:bold;">₹ <?php  echo  $FareBreakUp['WebPDiscount']['Value'];?></td>
			</tr>
	  </table>	

		<table style=" text-align: left;  width:100%; border-collapse: collapse; border: 1px solid #ddd;margin-bottom:20px;" >
			
			 
			  <?php foreach($FareBreakUp['FareBreakup'] as $fare) { ?>
			  <tr >
			     <td style="  padding: 10px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc"><?php  echo  $fare['LabelText'];?>:</td>
			       <td style="  padding: 10px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc">₹ <?php  echo  $fare['Value'];?></td>
			  </tr>
			  <?php }  ?>
			  <tr >
			     <td style="  padding: 10px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight: bold;"><?php  echo  $FareBreakUp['TotalAmount']['LabelText'];?>:</td>
			       <td style="  padding: 10px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight:bold;">₹ <?php  echo  $FareBreakUp['TotalAmount']['Value'];?></td>
			  </tr>
		</table>	
		
     <?php if(isset($FareBreakUp['GSTDetails']) && $FareBreakUp['GSTDetails']){ ?>
		<table style=" text-align: left;  width:100%; border-collapse: collapse; border: 1px solid #ddd;">
		
		   <tr>
			<th style="padding: 5px 12px;width:32%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">Service Description</th>
			<th style="padding: 5px 12px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">Taxable Value</th>
			<th style="padding: 5px 12px;width:15%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">CGST @ <?php echo $FareBreakUp['GSTDetails']['CGSTRate'];  ?> %</th>
			<th style="padding: 5px 12px;width:15%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">SGST @ <?php echo $FareBreakUp['GSTDetails']['SGSTRate'];  ?>%</th>
			<th style="padding: 5px 12px;width:13%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">IGST @<?php echo $FareBreakUp['GSTDetails']['IGSTRate'];  ?> %</th>
			<th style="padding: 5px 12px; width:10%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">Total</th>
			</tr>
		
		
		<tr>
			<th style="padding: 5px 12px; width:32%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">Service Charges</th>
			<th style="padding: 5px 12px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"><?php echo $FareBreakUp['GSTDetails']['TaxableAmount'];  ?></th>
			<th style="padding: 5px 12px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"><?php echo $FareBreakUp['GSTDetails']['CGSTAmount'];  ?></th>
			<th style="padding: 5px 12px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"> <?php echo $FareBreakUp['GSTDetails']['SGSTAmount'];  ?></th>
			<th style="padding: 5px 12px; width:13%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"> <?php echo $FareBreakUp['GSTDetails']['IGSTAmount'];  ?></th>
			<th style="padding: 5px 12px; width:10%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"> <?php echo $FareBreakUp['GSTDetails']['CGSTAmount']+$FareBreakUp['GSTDetails']['SGSTAmount']+$FareBreakUp['GSTDetails']['IGSTAmount'];  ?></th>
			</tr>
		
		</table>
		<?php }} ?>
								</div>  	
						      </div>
						    </div>
						 </div>
						 <div class="accordion-item">
						    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
						         <span class="acordian_heading">User Information : </span>
						   
						      </button>
						    <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
						      <div class="accordion-body cart-details-borderline">
						      	<div class="amend_details-passengers--list">
								 <?php   if(isset($amendmentDetail['travelersInfo'])) { $travelersInfo  =  $amendmentDetail['travelersInfo']; if($travelersInfo) {  ?>
						      		<div class="row">
								       	<div class="col-md-4">
								       	  	 <div class="cart_info-field">
								       	  	 	<p class="cart_info-field--title">Contact's Email:<span class="cart_info-field--detail"><span> <?php echo  $travelersInfo[0]['email_id']; ?></span></span></p>
								       	  	 </div>	
								       	</div>
								       	<div class="col-md-4">
								       	  	<div class="cart_info-field">
								       	  		<p class="cart_info-field--title">Pax contact:<span class="cart_info-field--detail"><span>  <?php echo  $travelersInfo[0]['mobile_number']; ?></span></span></p>
								       	  	</div>	
								       	</div>
								    </div>
								 <?php   }  } ?>
								</div>  	
						      </div>
						    </div>
						 </div>
					</div>
				</div>	
			</div>
		</div>
	</div>
</section>
								 </div>
								 </div>

<script>

function getFlightRefundCharges(evt,paxId){
	var flightgst  =  18;
	evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
   /*  if ((charCode > 31) && (charCode <= 48 || charCode > 57)) { */
var charge  =  parseInt(document.getElementById("charge_"+paxId).value);
var serviceCharge  =  parseInt(document.getElementById("service_charge_"+paxId).value);
var mealCharge  =  parseInt(document.getElementById("meal_charge_"+paxId).value);
var baggageCharge  =  parseInt(document.getElementById("baggage_charge_"+paxId).value);
var seatCharge  =  parseInt(document.getElementById("seat_charge_"+paxId).value);
var basefare  =  parseInt(document.getElementById("base_fare_"+paxId).getAttribute('fareCharge'));
var airline_tax =  parseInt(document.getElementById("airline_tax_"+paxId).getAttribute('airlineTaxCharge'));
var yq_tax  =  parseInt(document.getElementById("yq_tax_"+paxId).getAttribute('yqtaxCharge'));
var airline_service_charges  =  parseInt(document.getElementById("airline_service_charges_"+paxId).getAttribute('airlineServiceCharge'));
var airline_baggage_charges  =  parseInt(document.getElementById("airline_baggage_charges_"+paxId).getAttribute('airlineBaggageCharge'));
var airline_meal_charges  =  parseInt(document.getElementById("airline_meal_charges_"+paxId).getAttribute('airlineMealCharge'));
var airline_seat_charges =  parseInt(document.getElementById("airline_seat_charges_"+paxId).getAttribute('airlineSeatCharge'));
/* var TotalpaxFare  = parseInt((basefare+airline_tax+airline_service_charges+airline_baggage_charges+airline_meal_charges+airline_seat_charges)); */
var TotalpaxFare  = parseInt((basefare+airline_tax+airline_service_charges));
var serviceChargeGst  =  calculate_flight_gst(serviceCharge,flightgst);
var totalRefundAmount  =  parseInt((charge+serviceCharge+serviceChargeGst));
var ssrPrice  =  mealCharge+baggageCharge+seatCharge;
var refund   = (TotalpaxFare-totalRefundAmount);
if(!isNaN(serviceChargeGst))
{
 document.getElementById("service_charge_gst_"+paxId).value =  serviceChargeGst;
}
else{
	document.getElementById("service_charge_gst_"+paxId).value =  0;
}
 if(!isNaN(refund)){
	if(refund<0){
		$("[data-message]").addClass('error_popup').html("Please check refund amount value is negative.");
	}
	else{
		$("[data-message]").removeClass('error_popup').html("");
	}
  document.getElementById("refund_"+paxId).value = parseInt(refund)+parseInt(ssrPrice);
 }
 else{
	document.getElementById("refund_"+paxId).value =  0;
}
	/* } */
}
function calculate_flight_gst(serviceCharge,flightgst)
{
	var returnval=Math.round(((serviceCharge*flightgst)/100),2);
	return returnval;
}
</script>								 