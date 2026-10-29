<div style="width: 100%; height: auto; background: #bbbdc0; margin: auto; padding-top: 50px; padding-bottom: 50px;">
	<center
		style="width: 850px;  height: auto; position: relative; background: #fff; margin: auto;   box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); font-family: 'Poppins', sans-serif; padding: 20px">
		<table style=" text-align: left; width:100%; border-collapse: collapse; ">
			<tr>
				<td>
					<h5
						style="margin-bottom:20px; margin-top:20px; text-align: center; text-transform: capitalize; font-size: 15px;">
						Voucher</h5>
				</td>
			</tr>
		</table>
		<table style=" text-align: left; float: left; width:50%;  border-collapse: collapse;">
			<tr>
				<td>

					<img src="<?php echo $CompanyLogo; ?>" alt="<?php echo $CompanyName; ?>"
						style="width:auto; height:70px;">
				</td>
			</tr>
		</table>
		<table style=" text-align: left; width:50%;   border-collapse: collapse;">
			<tr>
				<td>

					<p style="margin:0px 0 ; font-size:13px; line-height: 1.4;text-align: right; font-weight: bold;">
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
					Reference number :</th>
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
				<th rowspan="0" style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px;">Booking
					status :</th>
				<td style=" padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; ">
					<?php echo $BookingStatus; ?>
				</td>
			</tr>
		</table>
		<table style=" text-align: left; float: left;  width:50%; border-collapse: collapse; border: 1px solid #ddd;">
			<tr>
				<th rowspan="0" style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px;">Payment
					Status :</th>
				<td style=" padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; ">
					<?php echo $PaymentStatus; ?>
				</td>
			</tr>

		</table>

		<table style=" text-align: left; float: left;  width:50%; border-collapse: collapse; border: 1px solid #ddd;">
			<tr>
				<th rowspan="0" style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px;">Package
					Type :</th>
				<td style=" padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; ">
					<?php echo $HolidayCategory; ?>
				</td>
			</tr>

		</table>
		<table style=" text-align: left; float: left;  width:50%; border-collapse: collapse; border: 1px solid #ddd;">
			<tr>
				<th rowspan="0" style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px;"> Lead
					Guest Name :</th>
				<td style=" padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; ">
					<?php echo $lead_pax; ?>
				</td>
			</tr>

		</table>
		<table style=" text-align: left; float: left;  width:50%; border-collapse: collapse; border: 1px solid #ddd;">
			<tr>
				<th rowspan="0" style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px;">Travel
					Date :</th>
				<td style=" padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; ">
					<?php echo $travel_date; ?>
				</td>
			</tr>

		</table>
		<table style=" text-align: left;   width:50%; border-collapse: collapse; border: 1px solid #ddd;">
			<tr>
				<th rowspan="0" style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px;">No. of
					Pax :</th>
				<td style=" padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px;">
					<?php echo $TravellersCount; ?> Pax
				</td>
			</tr>

		</table>
		<table style=" text-align: left;   width:100%; border-collapse: collapse; border: 1px solid #ddd;">
			<tr>
				<th rowspan="0" style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px;">Trip Name
					:</th>
				<td style=" padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px;">
					<?php echo $Name; ?>
				</td>
			</tr>

		</table>

		<?php if ($FareBreakUp) { ?>
			<table style=" text-align: left; width:100%;  border-collapse: collapse;">
				<tr>
					<td style="width: 100%;">

						<p
							style="margin:20px 0px; display: inline-block; text-align: left; text-transform: uppercase; font-weight: bold;">
							Price Summary</p>
					</td>
				</tr>
			</table>

			<?php

			foreach ($FareBreakUp['FareBreakup'] as $key => $data) { ?>
				<table style=" text-align: left;  float: left; width:50%; border: 1px solid #ddd; border-collapse: collapse;"
					cellspacing="0" cellpadding="0">
					<tr style="">
						<td style="padding: 5px 7px; font-weight: bold; font-size: 13px;">
							<?php echo $data['LabelText'] ?> :
						</td>
						<td style="padding: 5px 7px; text-align: right; font-weight: normal; font-size: 13px;">INR
							<?php echo $data['Value'] ?>
						</td>
					</tr>
				</table>
			<?php } ?>

			<table style=" text-align: left;  float: left; width:100%; border: 1px solid #ddd; border-collapse: collapse;"
				cellspacing="0" cellpadding="0">
				<tr style="">
					<td style="padding: 5px 7px; font-weight: bold; font-size: 13px;">
						<?php echo $FareBreakUp['TotalAmount']['LabelText'] ?> :
					</td>
					<td style="padding: 5px 7px; text-align: right; font-weight: normal; font-size: 13px;"> INR
						<?php echo $FareBreakUp['TotalAmount']['Value'] ?>
					</td>
				</tr>
			</table>
		<?php } ?>
		<table style=" text-align: left; width:100%;  border-collapse: collapse;">
			<tr>
				<td style="width: 100%;">

					<p
						style="margin:20px 0px; display: inline-block; text-align: left; text-transform: uppercase; font-weight: bold;">
						Description of Service</p>
				</td>
			</tr>
		</table>

		<table style=" text-align: left; float: left; width:70%; border-collapse: collapse; border: 1px solid #ddd;"
			cellspacing="0" cellpadding="0">
			<tr>
				<td style="padding: 5px 7px; font-weight: bold; font-size: 13px;">Package Name :</td>
				<td style="padding: 5px 7px; text-align: right; font-weight: normal; font-size: 13px;">
					<?php echo $Name; ?>
				</td>
			</tr>
		</table>
		<table style=" text-align: left;  float: left; width:30%; border: 1px solid #ddd; border-collapse: collapse;"
			cellspacing="0" cellpadding="0">
			<tr style="">
				<td style="padding: 5px 7px; font-weight: bold; font-size: 13px;">Duration :</td>
				<td style="padding: 5px 7px; text-align: right; font-weight: normal; font-size: 13px;">
					<?php echo $DayNights; ?>
				</td>
			</tr>
		</table>


		<!--<table style=" text-align: left; width:100%;  border-collapse: collapse;">
			<tr>
				<td style="width: 100%;">
					<p style="margin-bottom:20px; display: inline-block; text-align: left; text-transform: uppercase; font-weight: bold;">Accommodation</p>
				</td>
			</tr>
		</table>
		<table style=" text-align: left;  float: left; width:100%; border: 1px solid #ddd; border-collapse: collapse;" cellspacing="0" cellpadding="0">
			<tr style="">
				<th style="padding: 5px 7px; font-weight: bold; font-size: 14px;">Destination</th>
				<th style="padding: 5px 7px; text-align: right; font-weight: bold; font-size: 14px;"><?php /*echo $HolidayCategory; */ ?></th>
			</tr>
		</table>
		<table style=" text-align: left;  float: left; width:100%; border: 1px solid #ddd; border-collapse: collapse;" cellspacing="0" cellpadding="0">
			<?php /*foreach ($Accommodation as $key => $hotels) { */ ?>
				<tr>
					<td style="padding: 5px 7px; font-weight: normal; font-size: 14px;"><?php /*echo $hotels['city'] */ ?></td>
					<td style="padding: 5px 7px; text-align: right; font-weight: normal; font-size: 14px;"><?php /*echo $hotels['hotel'] */ ?></td>
				</tr>
			<?php /*} */ ?>
		</table>
-->
		<!--<table style=" text-align: left; width:100%;  border-collapse: collapse;">
			<tr>
				<td style="width: 100%;">
					<p style="margin-bottom:20px; display: inline-block; text-align: left; text-transform: uppercase; font-weight: bold;"> Meals </p>
				</td>
			</tr>
			<tr>
				<td style="width: 100%;">
					<ul style="font-size: 14px;">
						<li style="margin: 5px 0;">Breakfast at the Hotel</li>
						<li style="margin: 5px 0;">Dinner at Dhow Marina Cruise</li>
						<li style="margin: 5px 0;">Dinner at Desert Safari</li>
					</ul>
				</td>
			</tr>
		</table>-->

		<table style=" text-align: left; width:100%;  border-collapse: collapse;">
			<tr>
				<td style="width: 100%;">
					<p
						style="margin:20px 0px; display: inline-block; text-align: left; text-transform: uppercase; font-weight: bold;">
						Inclusion </p>
				</td>
			</tr>
			<tr>
				<td style="width: 100%;">
					<ul style="font-size: 14px;">
						<?php foreach ($Inclusion as $data) { ?>
							<li style="margin: 5px 0;">
								<?php echo $data['Title']; ?>
							</li>
						<?php } ?>

					</ul>
				</td>
			</tr>
		</table>

		<table style=" text-align: left; width:100%;  border-collapse: collapse;">
			<tr>
				<td style="width: 100%;">
					<p
						style="margin-bottom:20px; display: inline-block; text-align: left; text-transform: uppercase; font-weight: bold;">
						Exclusion </p>
				</td>
			</tr>
			<tr>
				<td style="width: 100%;">
					<ul style="font-size: 14px;">
						<?php foreach ($Exclusion as $data) { ?>
							<li style="margin: 5px 0;">
								<?php echo $data['Title']; ?>
							</li>
						<?php } ?>
					</ul>
				</td>
			</tr>
		</table>
		<?php foreach ($Itinerary as $key => $data) { ?>
			<table style=" text-align: left; width:100%;  border-collapse: collapse;">
				<?php if ($key == 0) { ?>
					<tr>
						<td style="width: 100%;">
							<p
								style="margin-bottom:20px; display: inline-block; text-align: left; text-transform: uppercase; font-weight: bold;">
								Day Wise Itinerary </p>
						</td>
					</tr>
				<?php } ?>
				<tr>
					<td style="width: 100%;">
						<p
							style="margin-bottom:20px; display: inline-block; text-align: left; text-transform: uppercase; font-weight: bold;">
							Day -
							<?php echo $key + 1; ?>
						</p>
					</td>

				</tr>
				<tr>
					<td style="width: 100%;">
						<p
							style="margin-bottom:0px; font-size: 13px; display: inline-block; text-align: left; text-transform: uppercase; font-weight: bold;">
							<?php echo 'City'; ?> :
							<?php echo $data['city']; ?> :
							<?php echo $data['itinerary_title']; ?>
						</p>
					</td>
				</tr>
				<tr>
					<td style="width: 100%;">
						<p
							style="margin-bottom:0px; font-size: 13px; display: inline-block; text-align: left; text-transform: uppercase; font-weight: bold;">
							<?php echo 'Title'; ?> :
							<?php echo $data['itinerary_title']; ?>
						</p>
					</td>
				</tr>

				<tr>
					<td style="width: 100%;">
						<p
							style="margin-bottom:0px; font-size: 13px; display: inline-block; text-align: left; text-transform: uppercase; font-weight: bold;">
							<?php echo 'Meal'; ?> :
							<?php echo $data['day_meal_plan']; ?>
						</p>
					</td>
				</tr>

				<?php if ($data['hotel_data']) {
					foreach ($data['hotel_data'] as $hotel_data) {
						?>
						<tr>
							<td style="width: 100%;">
								<p
									style="margin-bottom:0px; font-size: 13px; display: inline-block; text-align: left; text-transform: uppercase; font-weight: bold;">
									<?php echo 'Hotel'; ?> :
									<?php echo $hotel_data['hotel_name'] ?>
								</p>
							</td>
						</tr>


					<?php }
				} ?>


				<?php if ($data['flight_data']) {
					foreach ($data['flight_data'] as $flight_data) {
						?>
						<tr>
							<td style="width: 100%;">
								<p
									style="margin-bottom:0px; font-size: 13px; display: inline-block; text-align: left; text-transform: uppercase; font-weight: bold;">
									<?php echo 'Flight'; ?> :
									<?php echo $flight_data['origin'] . "({$flight_data['depart_time']})" . ' to ' . $flight_data['destination'] . "({$flight_data['arrival_time']})" . ' ' . '<b>Airline: </b>' . $flight_data['airline']; ?>
								</p>
							</td>
						</tr>
					<?php }
				} ?>


				<?php if ($data['transfer_data']) {
					foreach ($data['transfer_data'] as $transfer_data) {
						?>
						<tr>
							<td style="width: 100%;">
								<p
									style="margin-bottom:0px; font-size: 13px; display: inline-block; text-align: left; text-transform: uppercase; font-weight: bold;">
									<?php echo 'Transfer'; ?> :
									<?php echo $transfer_data['pickup'] . "({$transfer_data['pickuptime']})" . ' to ' . $transfer_data['drop']; ?>
								</p>
							</td>
						</tr>
					<?php }
				} ?>

				<tr>
					<td style="width: 100%;">
						<p
							style="margin-bottom:20px; font-size: 14px; display: inline-block; text-align: left; line-height: 1.4;">
							<?php echo $data['itinerary_description']; ?>
						</p>
					</td>
				</tr>
			</table>
		<?php } ?>

	</center>
</div>