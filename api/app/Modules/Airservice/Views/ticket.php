<style>
	@media print {
		body {
			-webkit-print-color-adjust: exact;
		}
	}

	svg {
		width: 100%;
		height: 50px;
	}
</style>
<div style="width: 100%; height: auto; background: #bbbdc0; margin: auto; padding-top: 50px; padding-bottom: 50px;">
	<center
		style="width: 850px;  height: auto; position: relative; background: #fff; margin: auto;   box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); font-family: 'Poppins', sans-serif; padding: 20px">
		<table style=" text-align: left; width:100%; border-collapse: collapse; ">
			<tr>
				<td>
					<h5
						style="margin-bottom:20px; margin-top:20px; text-align: center; text-transform: capitalize; font-size: 15px;">
						Ticket</h5>
				</td>
			</tr>
		</table>
		<table style=" text-align: left;  width:100%;  border-collapse: collapse;">
			<tr>
				<td>

					<img src="<?php echo $CompanyLogo; ?>" alt="<?php echo $CompanyName; ?>"
						style="width:auto; height:70px;">
				</td>

				<td>

					<p style="margin:0px 0 ; font-size:13px; line-height: 1.4; font-weight: bold;text-align: right;">
						Name :
						<?php echo $CompanyName; ?>
					</p>
					<?php if ($Address != "") { ?>
						<p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;text-align: right;">Address :
							<?php echo $Address; ?>,
							<?php echo $City; ?>,
							<?php echo $State; ?>,
							<?php echo $Country; ?>,
							<?php echo $Pincode; ?>
						</p>
					<?php } ?>
					<p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;text-align: right;">State :
						<?php echo $State; ?>
					</p>
					<?php if ($SupportNo != "") { ?>
						<p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;text-align: right;">Phone :
							<?php echo $SupportNo; ?>
						</p>
					<?php } ?>
					<?php if ($SupportEmail != "") { ?>
						<p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;text-align: right;">Email :
							<?php echo $SupportEmail; ?>
						</p>
					<?php } ?>
					<?php if ($GstNo != "") { ?>
						<p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;text-align: right; font-weight: bold;">
							GST Number :
							<?php echo $GstNo; ?>
						</p>
					<?php } ?>
				</td>
			</tr>
		</table>



		<table style=" text-align: left; float: left;  width:50%; border-collapse: collapse; border: 1px solid #ddd;">
			<tr>
				<th rowspan="0" style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px;">Booking
					Reference number : </th>
				<td style=" padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; ">
					<?php echo $BookingRefNumber; ?>
				</td>
			</tr>

		</table>
		<table style=" text-align: left; float: left;  width:50%; border-collapse: collapse; border: 1px solid #ddd;">
			<tr>
				<th rowspan="0" style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px;">Booking
					Date :</th>
				<td style=" padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; ">
					<?php echo $BookingDate; ?>
				</td>
			</tr>

		</table>
		<table style=" text-align: left; float: left;  width:50%; border-collapse: collapse; border: 1px solid #ddd;">
			<tr>
				<th rowspan="0" style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px;">Trip Type
					:</th>
				<td style=" padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; ">
					<?php echo $JourneyType; ?>
				</td>
			</tr>

		</table>
		<table style=" text-align: left;   width:50%; border-collapse: collapse; border: 1px solid #ddd;">
			<tr>
				<th rowspan="0" style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px;">
					GDS/Airline PNR :</th>
				<td style=" padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px;">
					<?php echo $Pnr; ?>
				</td>
			</tr>

		</table>
		<table style=" text-align: left; float: left;  width:50%; border-collapse: collapse; border: 1px solid #ddd;">
			<tr>
				<th rowspan="0" style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px;">Booking
					status :</th>
				<td style=" padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; ">
					<?php echo $Bookingstatus; ?>
				</td>
			</tr>

		</table>
		<table style=" text-align: left;   width:50%; border-collapse: collapse; border: 1px solid #ddd;">
			<tr>
				<th rowspan="0" style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px;">Payment
					Status :</th>
				<td style=" padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px;">
					<?php echo $PaymentStatus; ?>
				</td>
			</tr>

		</table>
		<table style=" text-align: left; float: left;  width:50%; border-collapse: collapse; border: 1px solid #ddd;">
			<tr>
				<th rowspan="0" style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px;">Fare Type
					:</th>
				<td style=" padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px;">
					<?php echo $FareRNRType; ?>
				</td>
			</tr>
		</table>
		<table style=" text-align: left; float: left;  width:50%; border-collapse: collapse; border: 1px solid #ddd;">
			<tr>
				<th rowspan="0" style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px;">
					Confirmation Number :</th>
				<td style=" padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px;">
					<?php echo $InvoiceNumber ?>
				</td>
			</tr>
		</table>
		<!--<table style=" text-align: left;   width:100%; border-collapse: collapse; >
			  <tr >
				<th rowspan="0" style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px;"></th>
				<td style=" padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px;"></td>
			  </tr>
			  
		</table>-->
		<?php foreach ($TicketInvoiceData as $key => $TravelersInfo) {
			if ($TravelersInfo['TravelersInfo']) {
				$journey = "";
				if (count($TicketInvoiceData) == 2) {
					if ($key == "OB") {
						$journey = "Onward";
					} else if ($key == "IB") {
						$journey = "Return";
					}
				}

				?>
				<br />
				<br />
				<h5
					style="text-align: left;text-transform: uppercase;font-size: 15px;font-weight: 600;padding: 9px 5px;margin: 0px 0px 0px 0px;display: flex;background: #ffffff;color: black;">
					<?php echo $journey; ?> Passenger Details
				</h5>
				<table style=" text-align: left;  width:100%; border-collapse: collapse;">
					<tr style="border: 1px solid #dddddd;">
						<th
							style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:13px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
							Passenger Name</th>
						<th
							style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:13px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
							Type</th>
						<th
							style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:13px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
							Ticket No.</th>
						<th
							style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:13px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
							DOB</th>
					</tr>

					<?php foreach ($TravelersInfo['TravelersInfo'] as $Travelers) {
						$date_of_birth = explode('T', $Travelers['date_of_birth']); ?>
						<tr style="border: 1px solid #dddddd;">
							<td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;">
								<?php echo $Travelers['title'] . " " . $Travelers['first_name'] . " " . $Travelers['last_name']; ?>
							</td>
							<td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;">
								<?php echo $Travelers['pax_type']; ?>
							</td>
							<td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;">
								<?php echo $Travelers['validating_airline']; ?>
								<?php echo $Travelers['ticket_number']; ?>
							</td>
							<td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;">
								<?php echo isset($date_of_birth[0]) ? $date_of_birth[0] : ''; ?>
							</td>
						</tr>
					<?php } ?>
				</table>
				<h5
					style="text-align: left;text-transform: uppercase;font-size: 15px;font-weight: 600;padding: 9px 5px;margin: 0px 0px 0px 0px;display: flex;background: #ffffff;color: black;">
					<?php echo $journey; ?> Contact Details
				</h5>
				<table style=" text-align: left;  width:100%; border-collapse: collapse;">
					<tr style="border: 1px solid #dddddd;">
						<th
							style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:13px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
							Email</th>
						<th
							style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:13px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
							Mobile Number</th>

					</tr>

					<?php foreach ($TravelersInfo['TravelersInfo'] as $Travelers) { ?>
						<tr style="border: 1px solid #dddddd;">
							<td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;">
								<?php echo $Travelers['email_id']; ?>
							</td>
							<td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;">
								<?php echo $Travelers['mobile_number']; ?>
							</td>
						</tr>
					<?php } ?>
				</table>
			<?php }
		} ?>

		<?php
		foreach ($TicketInvoiceData as $key => $Trips) {
			if ($Trips['Segments']) {
				$journey = "";
				if (count($TicketInvoiceData) == 2) {
					if ($key == "OB") {
						$journey = "Onward";
					} else if ($key == "IB") {
						$journey = "Return";
					}
				}

				?>
				<h5
					style="text-align: left;text-transform: uppercase;font-size: 15px;font-weight: 600;padding: 10px 5px;margin:0px">
					<?php echo $journey; ?> Flight Details
				</h5>
				<table style=" text-align: left; width:100%; border-collapse: collapse; ">
					<tr style="border: 1px solid #dddddd;">
						<th
							style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:13px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
							Flight</th>
						<th
							style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:13px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
							Departure</th>
						<th
							style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:13px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
							Arrival</th>
						<th
							style="padding: 5px 7px;text-align: left;font-weight: bold;font-size:13px;border-bottom:1px solid #ccc;border-top: 1px solid #ccc;background: #11458d;color: white;">
							Status</th>
					</tr>
					<?php foreach ($Trips['Segments'] as $trips) {
						foreach ($trips as $segmentIndicatorkey => $segment) { ?>
							<tr style="border: 1px solid #dddddd;">
								<td style=" padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;">
									<p style="margin:0px 0 ; font-size:13px; text-align: left; line-height: 1.4;">
										<?php echo $segment['Airline']['AirlineName']; ?>
										<?php echo $segment['Airline']['AirlineCode']; ?>-
										<?php echo $segment['Airline']['FlightNumber']; ?>
									</p>
									<p style="margin:0px 0 ; font-size:13px; text-align: left; line-height: 1.4;">Aircraft:
										<?php echo $segment['Craft']; ?>
									</p>
									<p style="margin:0px 0 ; font-size:13px; text-align: left; line-height: 1.4;">Class -
										<?php echo $segment['CabinClass']; ?>
									</p>
								</td>
								<td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;">
									<p style="margin:0px 0 ; font-size:13px; text-align: left; line-height: 1.4;">
										<?php echo $segment['Origin']['CityCode']; ?> (
										<?php echo $segment['Origin']['AirportName']; ?>,
										<?php echo $segment['Origin']['CityName']; ?>)
									</p>
									<p style="margin:0px 0 ; font-size:13px; text-align: left; line-height: 1.4;">
										<?php echo get_flight_time($segment['Origin']['DepartTime']); ?>,
										<?php echo get_flight_date($segment['Origin']['DepartTime']); ?>
									</p>
									<?php if ($segment['Origin']['Terminal'] != "") { ?>
										<p style="margin:0px 0 ; font-size:13px; text-align: left; line-height: 1.4;">Terminal -
											<?php echo $segment['Origin']['Terminal']; ?>
										</p>
									<?php } ?>
								</td>
								<td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;">
									<p style="margin:0px 0 ; font-size:13px; text-align: left; line-height: 1.4;">
										<?php echo $segment['Destination']['CityCode']; ?> (
										<?php echo $segment['Destination']['AirportName']; ?>,
										<?php echo $segment['Destination']['CityName']; ?>)
									</p>
									<p style="margin:0px 0 ; font-size:13px; text-align: left; line-height: 1.4;">
										<?php echo get_flight_time($segment['Destination']['ArrivalTime']); ?>,
										<?php echo get_flight_date($segment['Destination']['ArrivalTime']); ?>
									</p>
									<?php if ($segment['Destination']['Terminal'] != "") { ?>
										<p style="margin:0px 0 ; font-size:13px; text-align: left; line-height: 1.4;">Terminal -
											<?php echo $segment['Destination']['Terminal']; ?>
										</p>
									<?php } ?>
								</td>
								<td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;">
									<p style="margin:0px 0 ; font-size:13px; text-align: left; line-height: 1.4;">Confirmed</p>

									<?php if ($Trips['AirlinePNR']) {
										$TripIndicator = $segment['TripIndicator'];
										$SegmentIndicator = $segment['SegmentIndicator'];
										if (isset($Trips['AirlinePNR'][$TripIndicator][$SegmentIndicator]) && $Trips['AirlinePNR'][$TripIndicator][$SegmentIndicator]) { ?>
											<p style="margin:0px 0 ; font-size:13px; text-align: left; line-height: 1.4;">Airline Ref :
												<?php echo $Trips['AirlinePNR'][$TripIndicator][$SegmentIndicator]; ?>
											</p>
										<?php }
									} ?>

									<p style="margin:0px 0 ; font-size:13px; text-align: left; line-height: 1.4;">
										<?php echo get_convertToHoursMinsfromMinDuration($segment['Duration']); ?>
									</p>
								</td>
							</tr>
						<?php }
					} ?>
				</table>
			<?php }
		} ?>


		<!-- 		<table style=" text-align: left;  width:100%; border-collapse: collapse; border: 1px solid #ddd;" >
			<h5 style="margin-bottom:20px; margin-top: 20px; text-align: left; text-transform: uppercase; font-size: 15px; "> Excess Baggage & Meal Details</h5>
			  <tr >
				<th style="  padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border-bottom:1px solid #ccc">Name</th>
				<th style="  padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border-bottom:1px solid #ccc">Sector </th>
				<th style="  padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border-bottom:1px solid #ccc">Meal Name</th>
			  </tr>
			  <tr >
				 <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;">Mr asdas asdas  </td>
				 <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;">DEL-BOM </td>
				 <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;">Hindu Veg Meal (Qty :1)</td>
				 
			  </tr>
		</table>	
		
		<table style=" text-align: left;  width:100%; border-collapse: collapse; border: 1px solid #ddd;" >
			<h5 style="margin-bottom:20px; margin-top: 20px; text-align: left; text-transform: uppercase; font-size: 15px; "> Return Excess Baggage & Meal Details</h5>
			  <tr >
				<th style="  padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border-bottom:1px solid #ccc">Name</th>
				<th style="  padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border-bottom:1px solid #ccc">Sector </th>
				<th style="  padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border-bottom:1px solid #ccc">Meal Name</th>
			  </tr>
			  <tr >
				 <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;">Mr asdas asdas  </td>
				 <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;"></td>
				 <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;">Fruit Platter</td>
				 
			  </tr>
		</table> -->
		<?php if ($FareBreakUp) { ?>
			<h5
				style="text-align: left;text-transform: uppercase;font-size: 15px;font-weight: 600;padding: 9px 5px;margin: 0px 0px 0px 0px;display: flex;background: #ffffff;color: black;">
				Payment Details</h5>
			<table style=" text-align: left;  width:100%; border-collapse: collapse; padding:10px ;">
				<tr style="border: 1px solid #dddddd;">
					<th rowspan="<?php echo count($FareBreakUp['FareBreakup']) + 2; ?>"
						style="  padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border:1px solid #ccc">
						<p style="margin:0px 0 ; font-size:13px; text-align: left; line-height: 1.4;">This is an electronic
							ticket.</p>
						<p style="margin:0px 0 ; font-size:13px; text-align: left; line-height: 1.4;">Please carry a
							positive identification for check in.</p>
					</th>
				</tr>
				<?php foreach ($FareBreakUp['FareBreakup'] as $fare) { ?>
					<tr style="border: 1px solid #dddddd;">
						<td
							style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc">
							<?php echo $fare['LabelText']; ?>:
						</td>
						<td
							style="  padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc">
							₹
							<?php echo $fare['Value']; ?>
						</td>
					</tr>
				<?php } ?>
				<tr style="border: 1px solid #dddddd;">
					<td
						style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight: bold;">
						<?php echo $FareBreakUp['TotalAmount']['LabelText']; ?>:
					</td>
					<td
						style="  padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight:bold;">
						₹
						<?php echo $FareBreakUp['TotalAmount']['Value']; ?>
					</td>
				</tr>
			</table>
		<?php } ?>

		<?php if (0) { ?>
			<h5
				style="margin-bottom:20px; margin-top: 20px; text-align: left; text-transform: uppercase; font-size: 15px; font-weight: 600;">
				Rules and Conditions / Flight Note</h5>
			<table style=" text-align: left;  width:100%; border-collapse: collapse; padding:10px ;">
				<?php foreach ($TicketInvoiceData as $FareRuleData) {
					if ($FareRuleData['FareRule']) {
						foreach ($FareRuleData['FareRule'] as $FareRule) { ?>
							<tr style="border: 1px solid #dddddd;">
								<th
									style="  padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border-bottom:1px solid #ccc;">
									<?php echo $FareRule['Origin'] . "-" . $FareRule['Destination']; ?>
								</th>
							</tr>
							<tr style="border: 1px solid #dddddd;">
								<td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;">
									<?php echo $FareRule['FareRuleDetail']; ?>
								</td>
							</tr>
						<?php }
					}
				} ?>
			</table>
		<?php } ?>
		<h5
			style="margin-bottom:20px; margin-top: 20px; text-align: left; text-transform: uppercase; font-size: 15px; font-weight: 600; ">
			Important Information</h5>
		<table style=" text-align: left;  width:100%; padding:10px ;">
			<tr>
				<td>1. You should carry a print-out of your booking and present for check-in.</td>
			</tr>

			<tr>
				<td>2. Date &amp; Time is calculated based on the local time of city/destination.</td>
			</tr>
			<tr>
				<td>3. Use the Reference Number for all Correspondence with us.</td>
			</tr>
			<tr>
				<td>4. Use the Airline PNR for all Correspondence directly with the Airline</td>
			</tr>
			<tr>
				<td>5. For departure terminal please check with airline first.</td>
			</tr>
			<tr>
				<td>6. Please CheckIn atleast 2 hours prior to the departure for domestic flight and 3 hours prior to
					the departure of international flight.</td>
			</tr>
			<tr>
				<td>7. For rescheduling/cancellation within 4 hours of departure time contact the airline directly</td>
			</tr>
		</table>
	</center>
</div>