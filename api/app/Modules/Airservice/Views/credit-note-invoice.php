<div style="width: 100%; height: auto; background: #bbbdc0; margin: auto; padding-top: 50px; padding-bottom: 50px;">
	<center style="width: 850px;  height: auto; position: relative; background: #fff; margin: auto;   box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); font-family: 'Poppins', sans-serif; padding: 20px">
        <table style=" text-align: left; width:100%; border-collapse: collapse; ">
            <tr>
                <td>
                    <h5 style="margin-bottom:20px; margin-top:20px;  text-transform: capitalize; font-size: 15px;">
                        <b>Credit Note No-<?php echo $CreditNoteNo ?></b></br>
                        <b>Invoice Number-<?php echo $InvoiceNumber ?></b>
                    </h5>
                </td>
                <td>
                    <h5 style="margin-bottom:20px; margin-top:20px;  text-transform: capitalize; font-size: 15px;"><b>Credit Note Date-<?php echo $CreditNoteDate; ?></b></h5>
                </td>
                <td>
                    <h5 style="margin-bottom:20px; margin-top:20px; text-align: right; text-transform: capitalize; font-size: 15px;">
                        <b>PNR-<?php echo $Pnr; ?></b>
                    </h5>
                </td>
            </tr>
        </table>
		<table style=" text-align: left; width:100%; border-collapse: collapse; ">
			<tr>
				<td>
					<h5 style="margin-bottom:20px; margin-top:20px; text-align: center; text-transform: capitalize; font-size: 15px;">Credit Note</h5>
				</td>
			</tr>
		</table>
		<table style=" text-align: left;margin-bottom: 20px;  width:100%; float: left;   border-collapse: collapse;">
			<tr>
				<td>

					<p style="margin:0px 0 ; font-size:13px; line-height: 1.4;"><b>Name : <?php echo $SuperAdminCompanyName; ?></b></p>
					<p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;">Address : <?php echo $SuperAdminAddress; ?>,
						<?php echo $SuperAdminCity; ?><?php if ($SuperAdminState != "") {
							echo "," . $SuperAdminState;
						} ?><?php if ($SuperAdminCountry != "") {
							echo "," . $SuperAdminCountry;
						} ?><?php if ($SuperAdminPincode != "") {
							echo "," . $SuperAdminPincode;
						} ?></p>
					<?php if ($SuperAdminState != "") { ?>
						<p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;">State : <?php echo $SuperAdminState; ?></p>
					<?php } ?>
					<?php if ($SuperAdminState != "") { ?>
						<p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;">Phone : <?php echo $SuperAdminSupportNo; ?></p>
					<?php } ?>
					<?php if ($SuperAdminSupportEmail != "") { ?>
						<p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;">Email : <?php echo $SuperAdminSupportEmail; ?></p>
					<?php } ?>
					<?php if ($SuperAdminPanNo != "") { ?>
						<p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;"><b>PAN Number : <?php echo $SuperAdminPanNo; ?></b></p>
					<?php } ?>
					<?php if ($SuperAdminGstNo != "") { ?>
						<p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;"><b>GST Number : <?php echo $SuperAdminGstNo; ?></b></p>
					<?php } ?>
				</td>

				<td>

					<p style="margin:0px 0 ; font-size:13px; line-height: 1.4;text-align: right;"><b>Name : <?php echo $CompanyName; ?></b></p>
					<p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;text-align: right;">Address : <?php echo $Address; ?>,
						<?php echo $City; ?><?php if ($State != "") {
							echo "," . $State;
						} ?><?php if ($Country != "") {
							echo "," . $Country;
						} ?><?php if ($Pincode != "") {
							echo "," . $Pincode;
						} ?></p>
					<?php if ($State != "") { ?>
						<p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;text-align: right;">State : <?php echo $State; ?></p>
					<?php } ?>
					<?php if ($State != "") { ?>
						<p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;text-align: right;">Phone : <?php echo $SupportNo; ?></p>
					<?php } ?>
					<?php if ($SupportEmail != "") { ?>
						<p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;text-align: right;">Email : <?php echo $SupportEmail; ?></p>
					<?php } ?>
					<?php if ($PanNo != "") { ?>
						<p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;text-align: right;"><b>PAN Number : <?php echo $PanNo; ?></b></p>
					<?php } ?>
					<?php if ($GstNo != "") { ?>
						<p style="margin:0px 0 ; font-size:13px;  line-height: 1.4;text-align: right;"><b>GST Number : <?php echo $GstNo; ?></b></p>
					<?php } ?>
				</td>
			</tr>
		</table>


		<table style=" text-align: left; width:100%; border-collapse: collapse; border: 1px solid #ddd;">

			<tr>
				<th style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border-bottom:1px solid #ccc">Ticket No</th>

				<th style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border-bottom:1px solid #ccc"> Name</th>
				<th style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border-bottom:1px solid #ccc">Type</th>
				<th style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border-bottom:1px solid #ccc">Fare</th>
				<th style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border-bottom:1px solid #ccc">Tax</th>
				<th style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border-bottom:1px solid #ccc">YQ Tax</th>
				<th style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border-bottom:1px solid #ccc"> Service Ch.</th>
				<th style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border-bottom:1px solid #ccc"> Meal </th>
                <th style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border-bottom:1px solid #ccc"> Baggage Ch.</th>
                <th style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border-bottom:1px solid #ccc"> Seat Ch.</th>
                <th style="padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border-bottom:1px solid #ccc">Cancellation Ch.</th>
			</tr>
			<?php $cancellation_charges =0; $TotalRefund=0; if ($TicketInvoiceData) {
				$TDSReturnIdentifier  =  "no";
				foreach ($TicketInvoiceData as $InvoiceData) {
                    $Travelers = $InvoiceData['TravelersInfo'];
                    $fare = json_decode($Travelers['fare'],true);
                    $amendment_charges = json_decode($Travelers['amendment_charges'],true);

                    $cancellation_charges = $amendment_charges['Charge']+$amendment_charges['ServiceCharge'];
					$TDSReturnIdentifier =  isset($amendment_charges['TDSReturnIdentifier'])?$amendment_charges['TDSReturnIdentifier']:"no";
                    $TotalRefund = $amendment_charges['Refund'];
                    ?>
						<tr>
							<td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;"><?php echo $Travelers['ticket_number']; ?></td>
							<td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;"><?php echo $Travelers['title'] . " " . $Travelers['first_name'] . " " . $Travelers['last_name']; ?> </td>
							<td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;"><?php echo $Travelers['pax_type']; ?></td>
							<td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;"><?php echo $fare['BaseFare']; ?></td>
							<td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;"><?php echo $fare['Tax']; ?></td>
							<td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;"><?php echo $fare['YQTax']; ?></td>
							<td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;"><?php echo$fare['ServiceCharges']; ?></td>
							<td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;"><?php echo  $fare['MealCharges']; ?></td>
                            <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;"><?php echo $fare['BaggageCharges']; ?></td>

                            <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;"><?php echo $fare['SeatCharges']; ?></td>
                            <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px;"><?php echo  $cancellation_charges;?></td>

                        </tr>
					<?php
				}
			} ?>
		</table>


		<?php if ($FareBreakUp) { ?>
			<table style=" text-align: left;  width:100%; border-collapse: collapse; border: 1px solid #ddd;">
				<h5 style="margin-bottom:20px; margin-top: 20px; text-align: left; text-transform: uppercase; font-size: 15px;font-weight: 600; ">Payment Details</h5>
				<tr>
					<th rowspan="<?php echo count($FareBreakUp['FareBreakup']) + 8; ?>" style="  padding: 5px 7px; text-align: left; font-weight: bold; font-size:13px; border:1px solid #ccc">
						<p style="margin:0px 0 ; font-size:13px; text-align: left; line-height: 1.4;">This is an electronic ticket.</p>
						<p style="margin:0px 0 ; font-size:13px; text-align: left; line-height: 1.4;">Please carry a positive identification for check in.</p>
					</th>
				</tr>
				<?php foreach ($FareBreakUp['FareBreakup'] as $fare) { ?>
					<tr>
						<td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc"><?php echo $fare['LabelText']; ?>:</td>
						<td style="  padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc">₹ <?php echo $fare['Value']; ?></td>
					</tr>
				<?php } ?>
				<tr>
					<td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight: bold;"><?php echo $FareBreakUp['TotalAmount']['LabelText']; ?>:</td>
					<td style="  padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight:bold;">₹ <?php echo $FareBreakUp['TotalAmount']['Value']; ?></td>
				</tr>

                <tr>
                    <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight: bold;"><?php echo 'Cancellation Charges'; ?>:</td>
                    <td style="  padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight:bold;">₹ <?php echo $cancellation_charges; ?></td>
                </tr>

                <tr>
                    <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc">CGST @ <?php echo $amendment_charges['GST']['CGSTRate']; ?> % (-):</td>
                    <td style="  padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc">₹ <?php echo $amendment_charges['GST']['CGSTAmount']; ?></td>
                </tr>

                <tr>
                    <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc">SGST @ <?php echo $amendment_charges['GST']['SGSTRate']; ?> % (-):</td>
                    <td style="  padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc">₹ <?php echo $amendment_charges['GST']['SGSTAmount']; ?></td>
                </tr>

                <tr>
                    <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc">IGST @<?php echo $amendment_charges['GST']['IGSTRate']; ?> % (-):</td>
                    <td style="  padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc">₹ <?php echo $amendment_charges['GST']['IGSTAmount']; ?></td>
                </tr>
				<?php if($TDSReturnIdentifier=="no") { ?>
                <tr>
                    <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc"><?php echo "TDS"; ?> (-):</td>
                    <td style="  padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc">₹ <?php echo $FareBreakUp['FareBreakup']['TDS']['Value']; ?></td>
                </tr>
<?php  } ?>
                <tr>
                    <td style="  padding: 5px 7px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight: bold;"><?php echo 'Total Refund'; ?>:</td>
                    <td style="  padding: 5px 7px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight:bold;">₹ <?php echo $TotalRefund; ?></td>
                </tr>
			</table>

			<?php if (isset($amendment_charges['GST']) && $amendment_charges['GST']) { ?>
				<table style=" text-align: left;  width:100%; border-collapse: collapse; border: 1px solid #ddd;">

					<tr>
						<th style="padding: 5px 7px;width:32%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">Service Description</th>
						<th style="padding: 5px 7px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">Taxable Value</th>
						<th style="padding: 5px 7px;width:15%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">CGST @ <?php echo $amendment_charges['GST']['CGSTRate']; ?> %</th>
						<th style="padding: 5px 7px;width:15%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">SGST @ <?php echo $amendment_charges['GST']['SGSTRate']; ?>%</th>
						<th style="padding: 5px 7px;width:13%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">IGST @<?php echo $amendment_charges['GST']['IGSTRate']; ?> %</th>
						<th style="padding: 5px 7px; width:10%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">Total</th>
					</tr>


					<tr>
						<th style="padding: 5px 7px; width:32%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">Service Charges</th>
						<th style="padding: 5px 7px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"><?php echo $amendment_charges['GST']['TaxableAmount']; ?></th>
						<th style="padding: 5px 7px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"><?php echo $amendment_charges['GST']['CGSTAmount']; ?></th>
						<th style="padding: 5px 7px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"> <?php echo $amendment_charges['GST']['SGSTAmount']; ?></th>
						<th style="padding: 5px 7px; width:13%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"> <?php echo $amendment_charges['GST']['IGSTAmount']; ?></th>
						<th style="padding: 5px 7px; width:10%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"> <?php echo $amendment_charges['GST']['CGSTAmount'] + $amendment_charges['GST']['SGSTAmount'] + $amendment_charges['GST']['IGSTAmount']; ?></th>
					</tr>

				</table>
			<?php }
		} ?>
		<h5 style="margin-bottom:20px; margin-top: 20px; text-align: left; text-transform: uppercase; font-size: 15px; font-weight: bold; ">Terms & Conditions.</h5>
		<table style=" text-align: left;  width:100%; border-collapse: collapse; border: 1px solid transparent;">
			<tr>

				<td>
					<ul style="padding: 0; margin: 0;">
						<li style="list-style: disc;margin-left: 11px;">
							IMP : This is computer generated invoice signature not required
						</li>
						<li style="list-style: disc;margin-left: 11px;">
							IMP : All Cases & Disputes are subject to New Delhi Jurisdiction
						</li>
						<li style="list-style: disc;margin-left: 11px;">
							IMP :  Refunds & Cancellations are subject to Airlines approval
						</li>
						<li style="list-style: disc;margin-left: 11px;">
							IMP : Service charges as included above are to be collected from the customers on our behalf
						</li>
						<li style="list-style: disc;margin-left: 11px;">
							IMP :  Kindly check all details carefully to avoid un-necessary complications
						</li>
					</ul>
				</td>
			</tr>
		</table>

	</center>
</div>