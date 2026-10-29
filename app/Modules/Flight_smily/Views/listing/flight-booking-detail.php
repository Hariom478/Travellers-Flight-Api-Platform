<div class="page-content">
	<div class="table_title">

		<section class="cart_information">
			<div class="container">
				<div class="sale_bar">
					<div class="tts_row">
						<div class="tts-col-6">
							<h3> Flight Booking Details (<?php echo $bookingDetail['booking_ref_number']; ?>)</h3>
						</div>
						<div class="tts-col-6 text_right">
							<a class="btn btn-sm btn-info text-white" href="<?php echo site_url('/flight/confirmation/') . $ticketData = dev_encode(json_encode(array($bookingDetail['id']))); ?>">Booking Summary</a>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 col-12 col-lg-12">
						<div class="cart_info">
							<div class="accordion" id="accordionExample">
								<div class="accordion-item">
									<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
										<span class="acordian_heading">Cart Information : <?php echo $bookingDetail['booking_ref_number']; ?></span>
									</button>
									<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
										<div class="accordion-body cart-details-borderline">
											<div class="row">
												<div class="row">
													<div class="col-md-4 col-xs-6">
														<div class="cart_info-field">
															<p class="cart_info-field--title">Booking Ref Number :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $bookingDetail['booking_ref_number']; ?></span></span>
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
													<div class="col-md-4 col-xs-6">
														<div class="cart_info-field">
															<p class="cart_info-field--title">Refundable :<span class="cart_info-field--detail"><span class="<?php echo $bookingDetail['is_refundable'] == 1 ? "tts-text-success" : "tts-text-danger"; ?>"> &nbsp;<?php echo $bookingDetail['is_refundable'] == 1 ? "Yes" : "NO"; ?></span></span>
															</p>
														</div>
													</div>
													<div class="col-md-4 col-xs-6">
														<div class="cart_info-field">
															<p class="cart_info-field--title">Amount :<span class="cart_info-field--detail"><span> &nbsp;₹&nbsp;<?php echo $bookingDetail['total_price']; ?></span></span>
															</p>
														</div>
													</div>
													<div class="col-md-4 col-xs-6">
														<div class="cart_info-field">
															<p class="cart_info-field--title">Booking Status :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $bookingDetail['booking_status']; ?></span></span>
															</p>
														</div>
													</div>
													<div class="col-md-4 col-xs-6">
														<div class="cart_info-field">
															<p class="cart_info-field--title">Payment Status :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $bookingDetail['payment_status']; ?></span></span>
															</p>
														</div>
													</div>
													<div class="col-md-4 col-xs-6">
														<div class="cart_info-field">
															<p class="cart_info-field--title">Channel Type :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $bookingDetail['booking_channel']; ?></span></span>
															</p>
														</div>
													</div>
													<div class="col-md-4 col-xs-6">
														<div class="cart_info-field">
															<p class="cart_info-field--title">CreatedOn :<span class="cart_info-field--detail"><span> &nbsp;<?php echo date_created_format($bookingDetail['created']); ?></span></span>
															</p>
														</div>
													</div>
													<div class="col-md-4 col-xs-6">
														<div class="cart_info-field">
															<a href="<?php echo site_url('/flight/confirmation/') . $ticketData = dev_encode(json_encode(array($bookingDetail['id']))); ?>" class="">Booking Summary</a>
														</div>
													</div>
													<div class="col-md-4 col-xs-6">
														<div class="cart_info-field">
															<p class="cart_info-field--title">Booking User :<span class="cart_info-field--detail"><span> &nbsp;<a href="#" class=""><?php echo $bookingDetail['staff_name']; ?></a></span></span>
															</p>
														</div>
													</div>
													<?php if ($bookingDetail['last_ticket_date'] != "" && 0) { ?>
														<div class="col-md-4 col-xs-6">
															<div class="cart_info-field">
																<p class="cart_info-field--title">Last Ticket Date :<span class="cart_info-field--detail"><span> &nbsp;<a href="#" class="">    <?php echo $bookingDetail['last_ticket_date'] != "" ? display_custom_date_format($bookingDetail['last_ticket_date'], true) : ""; ?></a></span></span>
																</p>
															</div>
														</div>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- <div class="accordion-item">
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
								<div class="accordion-item">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
										<span class="acordian_heading">Cart Amendments</span>
										<span class="ball__mainwrapper"><span class="ball__border info_length-green"><span class="numbering-section"><?php echo count($amendmentList); ?></span></span></span>
										<div class="cssCircle addsign">
										<?php if($bookingDetail['booking_status']!="Cancelled" && $bookingDetail['booking_status']!="Processing") { ?>
										<span class="cssCircle-plusdesign"><a href="javascript:void(0);" data-bs-toggle="modal"
                                                 data-bs-target="#flight-raise-amendment">+ Raise Amendments</a></span></div>
												 <?php }  ?>
									</button>
									<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
										<div class="accordion-body cart-details-borderline">
										<?php if ($amendmentList) {
                                            foreach ($amendmentList as $amendment) {
                                                if ($amendment['staff_name']) {
                                                    $border = "tts-amendment-partner-border";
                                                    $remark = "txt-black";
                                                } else {
                                                    $border = "tts-amendment-admin-border";
                                                    $remark = "txt-black";
                                                }
                                                ?>

                                                <div class="accordion-body mb-1 cart-details-borderline <?php echo $border; ?>">
                                                    <div class="row amendment_box">


                                                        <div class="tts-col-3">
                                                            <p class="cart_info-field--title">Generation Time :
                                                                <span
                                                                        class="cart_info-field--detail"><?php echo date_created_format($amendment['created']); ?></span>
                                                            </p>
                                                        </div>
                                                        <div class="tts-col-3">
                                                            <p class="cart_info-field--title">Amendment Id : <span
                                                                        class="cart_info-field--detail"><?php echo $amendment['id']; ?></span>
                                                            </p>
                                                        </div>
                                                        <?php if ($amendment['staff_name']) { ?>
                                                            <div class="tts-col-3">
                                                                <p class="cart_info-field--title">User : <span
                                                                            class="cart_info-field--detail"><?php echo $amendment['staff_name']; ?></span>
                                                                </p>
                                                            </div>
                                                        <?php } ?>

                                                        <div class="tts-col-3">
                                                            <p class="cart_info-field--title">Status : <span
                                                                        class="cart_info-field--detail"><?php echo ucfirst($amendment['amendment_status']); ?></span>
                                                            </p>
                                                        </div>
                                                        <?php if ($amendment['remark_from_web_partner']) { ?>
                                                            <div class="tts-col-3">
                                                                <p class="cart_info-field--title text-danger">Remark
                                                                    : <span
                                                                            class="cart_info-field--detail <?php echo $remark; ?>"><?php echo $amendment['remark_from_web_partner']; ?></span>
                                                                </p>
                                                            </div>
                                                        <?php } ?>

                                                      

                                                        <?php if ($amendment['amendment_type']) { ?>
                                                            <div class="tts-col-3">
                                                                <p class="cart_info-field--title">Type : <span
                                                                            class="cart_info-field--detail"><?php echo ucwords(str_replace('_', ' ', $amendment['amendment_type'])); ?></span>
                                                                </p>
                                                            </div>
                                                        <?php } ?>
														<?php if ($amendment['id']) { ?>
                                                            <div class="tts-col-3">
                                                                <p class="cart_info-field--title"><a href  =  "<?php echo site_url('flight/amendment-details/').dev_encode($amendment['id']); ?>">View Detail</a>
                                                                </p>
                                                            </div>
                                                        <?php } ?>


														<?php if ($amendment['remark_from_super_admin']) { ?>
                                                            <div class="tts-col-12 amendment_reply">
                                                                <p class="cart_info-field--title text-danger">Reply Remark
                                                                    : <span
                                                                            class="cart_info-field--detail <?php echo $remark; ?>"><?php echo $amendment['remark_from_super_admin']; ?></span>
                                                                </p>
                                                            </div>
                                                        <?php } ?>


                                                    </div>
                                                </div>

                                                <?php if (isset($amendment['admin_reply'])) {
                                                    $border_admin = "tts-amendment-admin-border";
                                                    $remark_admin = "text-success";
                                                    foreach ($amendment['admin_reply'] as $amendment_reply) {
                                                        ?>
                                                        <div class="accordion-body mb-1 cart-details-borderline <?php echo $border_admin; ?>">
                                                            <div class="row">
                                                                <div class="tts-col-3">
                                                                    <p class="cart_info-field--title">Generation Time :
                                                                        <span  class="cart_info-field--detail"><?php echo date_created_format($amendment_reply['created']); ?></span>
                                                                    </p>
                                                                </div>
                                                                <div class="tts-col-3">
                                                                    <p class="cart_info-field--title">Amendment Id :
                                                                        <span
                                                                                class="cart_info-field--detail"><?php echo $amendment['id']; ?></span>
                                                                    </p>
                                                                </div>
                                                                <?php if ($amendment_reply['staff_name']) { ?>
                                                                    <div class="tts-col-3">
                                                                        <p class="cart_info-field--title">User : <span
                                                                                    class="cart_info-field--detail"><?php echo $amendment_reply['staff_name']; ?></span>
                                                                        </p>
                                                                    </div>
                                                                <?php } ?>

                                                                <div class="tts-col-3">
                                                                    <p class="cart_info-field--title">Status : <span
                                                                                class="cart_info-field--detail"><?php echo ucfirst($amendment_reply['amendment_status']); ?></span>
                                                                    </p>
                                                                </div>
                                                                <?php if ($amendment_reply['remark_from_web_partner']) { ?>
                                                                    <div class="tts-col-3">
                                                                        <p class="cart_info-field--title text-danger">
                                                                            Remark
                                                                            : <span
                                                                                    class="cart_info-field--detail <?php echo $remark_admin; ?>"><?php echo $amendment_reply['remark_from_web_partner']; ?></span>
                                                                        </p>
                                                                    </div>
                                                                <?php } ?>

                                                                <?php if ($amendment_reply['remark_from_super_admin']) { ?>
                                                                    <div class="tts-col-3">
                                                                        <p class="cart_info-field--title text-danger">
                                                                            Remark
                                                                            : <span
                                                                                    class="cart_info-field--detail <?php echo $remark_admin; ?>"><?php echo $amendment_reply['remark_from_super_admin']; ?></span>
                                                                        </p>
                                                                    </div>
                                                                <?php } ?>

                                                                <?php if ($amendment_reply['amendment_type']) { ?>
                                                                    <div class="tts-col-3">
                                                                        <p class="cart_info-field--title">Type : <span
                                                                                    class="cart_info-field--detail"><?php echo ucwords(str_replace('_', ' ', $amendment_reply['amendment_type'])); ?></span>
                                                                        </p>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                } ?>
                                            <?php }
                                        } ?>
										</div>
									</div>
								</div>
								<div class="accordion-item">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
										<span class="acordian_heading">Booking Details</span>

									</button>
									<div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
										<div class="accordion-body cart-details-borderline">
											<?php if (isset($bookingDetail['segments'])) {
												$tripInfo = json_decode($bookingDetail['segments'], true);
												foreach ($tripInfo as $key => $trips) {
													if ($trips) {
														foreach ($trips as $segmentIndicatorkey => $segment) { ?>
															<div class="row">
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
											<div class="amend_details-passengers--list">
												<?php if (isset($bookingDetail['travelersInfo'])) {
													$travelersInfo = json_decode($bookingDetail['travelersInfo'], true);
													foreach ($travelersInfo as $paxkey => $traveler) {
														$fare = json_decode($traveler['fare'], true);
														?>
														<div class="row">
															<div class="col-md-4">
																<div class="amend_passenger_details">
																	<span>Last Name/First Name Title</span>
																	<div class="person-name d-flex align-items-center justify-content-between">
																		<span class=""><?php echo $paxkey + 1; ?>. <?php echo $traveler['last_name']; ?>/<?php echo $traveler['first_name']; ?> <?php echo $traveler['title']; ?>. (<?php echo $traveler['pax_type']; ?>)</span>
																	</div>
																	<div class="row">
																		<?php if ($traveler['date_of_birth'] != NULL) { ?>
																			<div class="col-md-6">
																				<span class="sm_font padd-left-amendment">DOB :
																					<span class="bold"><?php echo $traveler['date_of_birth'] != "" ? display_custom_date_format($traveler['date_of_birth'], false) : "-"; ?></span></span>
																			</div>
																		<?php } ?>
																		<?php if ($traveler['pan_number'] != NULL) { ?>
																			<div class="col-md-6">
																				<span class="sm_font padd-left-amendment">Pan Number :
																					<span class="bold"><?php echo $traveler['pan_number']; ?></span></span>
																			</div>
																		<?php } ?>
																		<?php if ($traveler['passport_number'] != NULL) { ?>
																			<div class="col-md-6">
																				<span class="sm_font padd-left-amendment">Passport Number :
																					<span class="bold"><?php echo $traveler['passport_number']; ?></span></span>
																			</div>
																		<?php } ?>
																		<?php if ($traveler['passport_expiry'] != NULL) { ?>
																			<div class="col-md-6">
																				<span class="sm_font padd-left-amendment">Passport Expiry :
																					<span class="bold"><?php echo $traveler['passport_expiry'] != "" ? display_custom_date_format($traveler['passport_expiry'], false) : "-"; ?></span></span>
																			</div>
																		<?php } ?>
																	</div>
																</div>
															</div>
															<div class="col-sm-8 passenger_faredetail">
																<div class="row">
																	<div class="col-sm-2 col-xs-6 padd-left-amendment">
																		<p class="mg_right-50">Base Fare</p>
																		<p class="price-width-left text-right">₹ <?php echo $fare['BaseFare']; ?></p>
																	</div>
																	<div class="col-sm-2 col-xs-6 padd-left-amendment">
																		<p class="mg_right-50">Taxes</p>
																		<p class="price-width-left text-right">₹ <?php echo $fare['Tax']; ?></p>
																	</div>
																	<div class="col-sm-2 col-xs-6 padd-left-amendment">
																		<p class="mg_right-50">YQ Tax</p>
																		<p class="price-width-left text-right">₹ <?php echo $fare['YQTax']; ?></p>
																	</div>
																	<div class="col-sm-2 col-xs-6 padd-left-amendment">
																		<p class="mg_right-50">Service Charges</p>
																		<p class="price-width-left text-right">₹ <?php echo $fare['ServiceCharges']; ?></p>
																	</div>
																	<?php if ($fare['BaggageCharges']) { ?>
																		<div class="col-sm-2 col-xs-6 padd-left-amendment">
																			<p class="mg_right-50">Baggage Charges</p>
																			<p class="price-width-left text-right">₹ <?php echo $fare['BaggageCharges']; ?></p>
																		</div>
																	<?php } ?>
																	<?php if ($fare['MealCharges']) { ?>
																		<div class="col-sm-2 col-xs-6 padd-left-amendment">
																			<p class="mg_right-50">Meal Charges</p>
																			<p class="price-width-left text-right">₹ <?php echo $fare['MealCharges']; ?></p>
																		</div>
																	<?php } ?>
																	<?php if ($fare['SeatCharges']) { ?>
																		<div class="col-sm-2 col-xs-6 padd-left-amendment">
																			<p class="mg_right-50">Seat Charges</p>
																			<p class="price-width-left text-right">₹ <?php echo $fare['SeatCharges']; ?></p>
																		</div>
																	<?php } ?>
																	<?php if ($bookingDetail['pnr'] != Null) { ?>
																		<div class="col-sm-2 col-xs-6 padd-left-amendment">
																			<p class="mg_right-50"> PNR</p>
																			<p class="price-width-left text-right"> <?php echo $bookingDetail['pnr']; ?></p>
																		</div>
																	<?php } ?>
																	<?php if ($bookingDetail['pnr'] != Null) { ?>
																		<div class="col-sm-2 col-xs-6 padd-left-amendment">
																			<p class="mg_right-50">Airline PNR</p>
																			<p class="price-width-left text-right"> <?php   $gdsPnr  =  json_decode($bookingDetail['airline_pnr'],true);  echo $gdsPnr = getGdsPnr($gdsPnr);?></p>
																		</div>
																	<?php } ?>
																	<?php if ($traveler['ticket_number'] != Null) { ?>
																		<div class="col-sm-2 col-xs-6 padd-left-amendment">
																			<p class="mg_right-50">Ticket Number</p>
																			<p class="price-width-left text-right"><?php echo $traveler['ticket_number']; ?></p>
																		</div>
																	<?php } ?>
																	<?php if ($traveler['baggage'] != Null) { ?>
																		<div class="col-sm-2 col-xs-6 padd-left-amendment">
																			<p class="mg_right-50">Baggage</p>
																			<p class="price-width-left text-right"><?php echo $traveler['baggage']; ?></p>
																		</div>
																	<?php } ?>
																	<?php if ($traveler['meal'] != Null) { ?>
																		<div class="col-sm-2 col-xs-6 padd-left-amendment">
																			<p class="mg_right-50">Meal</p>
																			<p class="price-width-left text-right"><?php echo $traveler['meal']; ?></p>
																		</div>
																	<?php } ?>
																</div>
															</div>
														</div>
													<?php }
												} ?>
											</div>
										</div>
									</div>
								</div>
								<?php
								$paymentInfo = array();
								if (isset($bookingDetail['paymentInfo']) && $bookingDetail['paymentInfo']) {
									$paymentInfo = $bookingDetail['paymentInfo'];

								}
								?>
								 <?php if (!empty($paymentInfo) && is_array($paymentInfo)) { ?>
								<div class="accordion-item">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
										<span class="acordian_heading">Payment Process</span>
										<span class="ball__mainwrapper"><span class="ball__border info_length-green"><span class="numbering-section"><?php echo count($paymentInfo); ?></span></span></span>
									</button>
									<div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
										<div class="accordion-body cart-details-borderline">
											<div class="row">
												<div class="col-md-12">
													<div class="table-responsive">
														<table class="table table-bordered ">
															<thead>
															<tr>
																<th>Ref. Number</th>
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
																		<td><?php echo $data['remark']; ?></td>
																		<td> ₹ <?php echo $data['credit']; ?></td>
																		<td> ₹ <?php echo $data['debit']; ?></td>
																		<td><?php echo ucfirst($data['action_type']); ?></td>
																		<td>
																			<?php echo date_created_format($data['created']); ?>
																		</td>
																	</tr>
																<?php }
															} else {
																echo "<tr> <td colspan='6' class='text_center'><b>No Booking Found</b></td></tr>";
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
										<span class="acordian_heading">Fare Breakup </span>

									</button>
									<div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
										<div class="accordion-body cart-details-borderline">
											<div class="">
												<?php $FareBreakUp = $bookingDetail['FareBreakUp'];
												if ($FareBreakUp) { ?>
													<table style=" text-align: left;  width:100%; border-collapse: collapse; border: 1px solid #ddd;margin-bottom:20px;">
														<tr>
															<td style="  padding: 10px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight: bold;"><?php echo $FareBreakUp['AgentWebPMarkUp']['LabelText']; ?>:</td>
															<td style="  padding: 10px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight:bold;">₹ <?php echo $FareBreakUp['AgentWebPMarkUp']['Value']; ?></td>
														</tr>
														<tr>
															<td style="  padding: 10px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight: bold;"><?php echo $FareBreakUp['AgentWebPDiscount']['LabelText']; ?>:</td>
															<td style="  padding: 10px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight:bold;">₹ <?php echo $FareBreakUp['AgentWebPDiscount']['Value']; ?></td>
														</tr>
														<tr>
															<td style="  padding: 10px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight: bold;"><?php echo $FareBreakUp['AgentWebPDisplayMarkup']['LabelText']; ?>:</td>
															<td style="  padding: 10px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight:bold;"> <?php echo $FareBreakUp['AgentWebPDisplayMarkup']['Value']; ?></td>
														</tr>
													</table>

													<table style=" text-align: left;  width:100%; border-collapse: collapse; border: 1px solid #ddd;margin-bottom:20px;">


														<?php foreach ($FareBreakUp['FareBreakup'] as $fare) { ?>
															<tr>
																<td style="  padding: 10px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc"><?php echo $fare['LabelText']; ?>:</td>
																<td style="  padding: 10px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc">₹ <?php echo $fare['Value']; ?></td>
															</tr>
														<?php } ?>
														<tr>
															<td style="  padding: 10px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight: bold;"><?php echo $FareBreakUp['TotalAmount']['LabelText']; ?>:</td>
															<td style="  padding: 10px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight:bold;">₹ <?php echo $FareBreakUp['TotalAmount']['Value']; ?></td>
														</tr>
													</table>

													<?php if (isset($FareBreakUp['GSTDetails']) && $FareBreakUp['GSTDetails']) { ?>
														<table style=" text-align: left;  width:100%; border-collapse: collapse; border: 1px solid #ddd;">

															<tr>
																<th style="padding: 5px 12px;width:32%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">Service Description</th>
																<th style="padding: 5px 12px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">Taxable Value</th>
																<th style="padding: 5px 12px;width:15%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">CGST @ <?php echo $FareBreakUp['GSTDetails']['CGSTRate']; ?> %</th>
																<th style="padding: 5px 12px;width:15%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">SGST @ <?php echo $FareBreakUp['GSTDetails']['SGSTRate']; ?>%</th>
																<th style="padding: 5px 12px;width:13%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">IGST @<?php echo $FareBreakUp['GSTDetails']['IGSTRate']; ?> %</th>
																<th style="padding: 5px 12px; width:10%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">Total</th>
															</tr>


															<tr>
																<th style="padding: 5px 12px; width:32%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">Service Charges</th>
																<th style="padding: 5px 12px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"><?php echo $FareBreakUp['GSTDetails']['TaxableAmount']; ?></th>
																<th style="padding: 5px 12px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"><?php echo $FareBreakUp['GSTDetails']['CGSTAmount']; ?></th>
																<th style="padding: 5px 12px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"> <?php echo $FareBreakUp['GSTDetails']['SGSTAmount']; ?></th>
																<th style="padding: 5px 12px; width:13%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"> <?php echo $FareBreakUp['GSTDetails']['IGSTAmount']; ?></th>
																<th style="padding: 5px 12px; width:10%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"> <?php echo $FareBreakUp['GSTDetails']['CGSTAmount'] + $FareBreakUp['GSTDetails']['SGSTAmount'] + $FareBreakUp['GSTDetails']['IGSTAmount']; ?></th>
															</tr>

														</table>
													<?php }
												} ?>
											</div>
										</div>
									</div>
								</div>
								<div class="accordion-item">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
										<span class="acordian_heading">User Information </span>

									</button>
									<div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
										<div class="accordion-body cart-details-borderline">
											<div class="amend_details-passengers--list">
												<?php if (isset($bookingDetail['travelersInfo'])) {
													$travelersInfo = json_decode($bookingDetail['travelersInfo'], true);
													if ($travelersInfo) { ?>
														<div class="row">
															<div class="col-md-4">
																<div class="cart_info-field">
																	<p class="cart_info-field--title">Contact's Email:<span class="cart_info-field--detail"><span> <?php echo $travelersInfo[0]['email_id']; ?></span></span>
																	</p>
																</div>
															</div>
															<div class="col-md-4">
																<div class="cart_info-field">
																	<p class="cart_info-field--title">Pax contact:<span class="cart_info-field--detail"><span>  <?php echo $travelersInfo[0]['mobile_number']; ?></span></span>
																	</p>
																</div>
															</div>
														</div>
													<?php }
												} ?>
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
<div class="modal fade" id="flight-raise-amendment" tabindex="-1" aria-labelledby="flight-raise-amendmentLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="flight-raise-amendmentLabel">AMENDMENTS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo site_url('flight/raise-amendment'); ?>" method="post"
                  tts-form="true" name="flight-raise-amendment">
                <div class="modal-body">
					<div class  = ""mb-3">
					<label class="col-form-label">Amendment Type</label>
					<input type="text" name="booking_ref_number"
                               value="<?php echo $bookingDetail['booking_ref_number']; ?>"  class  =  "form-control" readonly>
					</div>
                    <div class="mb-3">
                        <label class="col-form-label">Amendment Type</label>
                        <select class="form-select" name="amendment_type"  data-validation  =  "required"  data-validation-error-msg-required="Please select Amendment Type">
                            <option value="">Amendment Type</option>
                            <option value="cancellation">Cancellation</option>
                            <option value="full_refund">Full Refund</option>
                            <option value="reissue">Re-Issue</option>
							<option value="correction">Correction</option>
							<option value="no_show">No Show</option>
                            <option value="cancellation_quotation">Cancellation Quotation</option> 
                        </select>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Raise</button>
                </div>
            </form>
        </div>
    </div>
</div>