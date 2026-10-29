<div marginwidth="0" margin="0">
	<center>
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="height: 100%; background: #bbbdc0;">
			<tbody>
				<tr>
					<td align="center" valign="top" style="padding-bottom: 40px;padding-top: 60px;">
						<center style="width: 600px;height: auto;position: absolute;top: 50%;left: 50%;background: #fff;box-shadow: 0px 0px 2px 0px grey;transform: translate(-50%, -50%);font-family: 'Poppins', sans-serif;">
							<table style=" text-align: left; width:100%; padding: 10px; border-collapse: collapse;">
								<tr>
									<td style="width: 100%; padding: 10px;">
										<div style="text-align: center;">
											<img src="<?php echo root_url . 'uploads/logo/' . super_admin_website_setting['company_logo'] ?>" alt="logo.png" class="CToWUd" data-bit="iit" style="margin-bottom:10px; width:250px; height: 100px; object-fit: contain;">
										</div>
										<p style="margin: 0; text-align: center; text-transform: uppercase; font-size: 24px;">Happy to have you with us!</p>
									</td>
								</tr>

							</table>
							<?php  if(isset($passwordMessage) && $passwordMessage): ?>
								<h4 style="color:#333;margin:10px 0"> <?php echo $passwordMessage; ?></h4>
								<?php else: ?>
									<p style="color:#333;margin: 0px 0;text-align: justify;padding: 20px;line-height: 22px;"><?php echo $message ? nl2br($message) : ''; ?></p>
							<?php endif ?>
							<table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%">
								<tbody>
									<tr>
										<td valign="top">
											<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%">
												<tbody>
													<tr>
														<td style="padding-top:9px;padding-left:18px;padding-bottom:9px;padding-right:18px">
															<table border="0" cellspacing="0" width="100%" style="min-width:100%!important;background-color:#ffffff">
																<tbody>
																	<tr>
																		<td valign="top" style="padding:0;color:#000000;font-family:Helvetica;font-size:14px;font-weight:normal;line-height:150%;text-align:left">
																			<table style="width:100%;border:1px solid #000000;border-collapse:collapse">
																				<tbody>
																					<tr>
																						<th style="border:1px solid #000000;border-collapse:collapse;padding:6px"> Email Id </th>
																						<td style="border:1px solid #000000;border-collapse:collapse;padding:6px"><?php echo $email; ?></td>
																					</tr>
																					<tr>
																						<th style="border:1px solid #000000;border-collapse:collapse;padding:6px"> Password </th>
																						<td style="border:1px solid #000000;border-collapse:collapse;padding:6px"><?php echo $password; ?></td>
																					</tr>
																					<?php if(isset($mobile_no) && $mobile_no): ?>
																					<tr>
																						<th style="border:1px solid #000000;border-collapse:collapse;padding:6px"> Mobile No </th>
																						<td style="border:1px solid #000000;border-collapse:collapse;padding:6px"> <?php echo $mobile_no; ?></td>
																					</tr> 
																					<?php endif ?>
																				</tbody>
																			</table>
																		</td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
							<table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%">
								<tbody>
									<tr>
										<td valign="top" style="padding-top:0px">
											<table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%;min-width:100%" width="100%">
												<tbody>
													<tr>
														<td valign="top" style="padding:0px 18px 9px;font-size:14px;font-style:normal;line-height:1.4">
															<div style="text-align:left"> For any further assistance, please contact us at <a href="tel:<?php echo super_admin_website_setting['support_no']; ?>" target="_blank"><?php echo super_admin_website_setting['support_no']; ?></a> or send an email to <a href="mailto:<?php echo super_admin_website_setting['support_email']; ?>" target="_blank"><?php echo super_admin_website_setting['support_email']; ?> </a>
															</div>
														</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
							<table style=" text-align: left; width:100%; padding: 10px; border-collapse: collapse;">
								<tr>
									<td style="width: 100%; padding: 15px;">
										<p style="text-align: left; margin: 0;  padding-bottom: 0px;"> <span> <span>Thanks &amp; Regards,</span> <br> <span>Team <span class="il"><?php echo super_admin_website_setting['company_name'] ?></span></span> </span>
										</p>
										<h4 style="text-align: center; "> Note: This is an auto generated email, please do not reply </h4>
									</td>
								</tr>
							</table>
							<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;">
								<tr>
									<td style="width:100%;">
										<table style="width: 100%; background-color: #3e3e3e;">
											<tbody>
												<tr>
													<td style=" padding: 10px 10px 10px 10px; color: #ff1010;  font-weight: normal; text-align: center; ">
														<h3 style="color: #fff; margin-top: 0px; text-align: center; margin-bottom: 0;"> <span> <span style="color: #fff; font-size: 12px; font-weight: 500;"> Need More help? We are Here, Ready to Talk. </span> </span>
														</h3>
														<h2 style="margin: 0px; text-align: center; padding: 0; font-weight: 500; line-height: 1;">
															<span style="font-size: 12px; color: #fff; margin-right: 30px;">
																<a href="tel:<?php echo super_admin_website_setting['support_no'] ?>" style="color: #fff;" target="_blank">
																	<img src="<?php echo root_url . 'uploads/icons/' . 'call-icon.png' ?>" style="vertical-align: middle; margin-right: 5px; width: 15px;" alt="phone.png" class="CToWUd" data-bit="iit"> <?php echo super_admin_website_setting['support_no'] ?>
																</a>
															</span>
															<span>
																<a href="mailto:<?php echo super_admin_website_setting['support_email'] ?>" style="color: #fff; font-size: 12px;" target="_blank"> <img src="<?php echo root_url . 'uploads/icons/' . 'email-icon.png' ?>" style="vertical-align: middle; margin-right: 5px; width: 15px;" alt="mail.png" class="CToWUd" data-bit="iit"> <span class="il"><?php echo super_admin_website_setting['support_email'] ?></span>.
																</a>
															</span>
														</h2>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</table>
						</center>
					</td>
				</tr>
			</tbody>
		</table>
	</center>
</div>