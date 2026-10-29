<div class="content ">
	<div class="page-content">
		<div class="table_title">
			<div class="sale_bar">
				<div class="row">
					<div class="col-md-4">
						<h5 class="m-0"> Debit Notes</h5>
					</div>
				</div>
			</div>
		</div>
		<div class="page-content-area">
			
				<!----------Start Search Bar ----------------->
				<form class="row g-3 mb-3" action="<?php echo site_url('webpartneraccounts/get-web-partner-debit-info'); ?>" method="GET" class="tts-dis-content" name="web-partner-search" onsubmit="return searchvalidateForm()">

					<div class="col-md-3">
						<div class="form-group">
							<label class="form-label">From Date</label>
							<input type="text" data-searchbar-from="true" name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
								echo $search_bar_data['from_date'];
							} ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" tts-validatation="Required" readonly/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="form-label">To Date</label> <input type="hidden" name="export_excel" value="0">
							<input type="text" data-searchbar-to="true" name="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
								echo $search_bar_data['to_date'];
							} ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" tts-validatation="Required" readonly/>
						</div>
					</div>
					<div class="col-md-2 align-self-end">
						<div class="form-group">

							<button type="submit" class="badge badge-md badge-primary badge_search" onclick="noExportExcel()">Search <i class="fa-solid fa-search"></i></button>
						</div>
					</div>
                    <? if (isset($search_bar_data['from_date'])): ?>
                        <div class="col-md-2">
                            <div class="search-reset-btn">
                                <a href="<?php echo site_url('webpartneraccounts/get-web-partner-account-info'); ?>">Reset
                                    Search</a>
                            </div>
                        </div>
                    <? endif ?>
                </form>
				</form>
		

			<!----------End Search Bar ----------------->
			<?php if ($account_logs) { ?>
				<div class="card-body">
					<div class="vewmodelhed mb_10">
						<div class="row mb-3">
							<div class="col-md-3">
								<div class="vi_mod_dsc">
									<span> Company Name:</span> <span class="primary">
										<b><?php echo $details['company_name']; ?></b> </span>
								</div>
							</div>
							<div class="col-md-3">
								<div class="vi_mod_dsc">
									<span>Pan Name:</span> <span class="primary">
										<b><?php echo $details['pan_name']; ?></b> </span>
								</div>
							</div>
							<div class="col-md-3">
								<div class="vi_mod_dsc">
									<span>Pan No:</span> <span class="primary">
										<b><?php echo $details['pan_number'] ?> </b> </span>
								</div>
							</div>
							<div class="col-md-3">
								<div class="vi_mod_dsc">
									<span>Available Balance:</span> <span class="primary">
										<b><?php echo $available_balance; ?> </b> </span>
								</div>
							</div>
						</div>
					</div>
					<div class="table-responsive">

					<table class="table table-bordered table-hover">
                     <thead class="table-active">
                        <tr>
                           <th>Ref.No.</th>                           
                           <th>Remark</th>
                           <th>Debit</th>
                           <th>Balance</th>
                           <th>Payments Type</th>
                           <th>Created</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                           if (!empty($account_logs) && is_array($account_logs)) {
                           	foreach ($account_logs as $data) {
                                                        $prefix_booking_ref_number = '';
                           
                                                        $booking_id =$data['booking_ref_no'];
                                                        if ($data['service'] == 'flight') {
                                                            $prefix_booking_ref_number = $data['flight_booking_ref_number'];
                           
                                                        }
                                                        if ($data['service'] == 'hotel') {
                                                            $prefix_booking_ref_number = $data['hotel_booking_ref_number'];
                                                        }
                           
                                                        if ($data['service'] == 'bus') {
                                                            $prefix_booking_ref_number = $data['bus_booking_ref_number'];
                                                        }
                           
                                                        if ($data['service'] == 'holiday') {
                                                            $prefix_booking_ref_number = $data['holiday_booking_ref_number'];
                                                        }
                           
                                                        if ($data['service'] == 'visa') {
                                                            $prefix_booking_ref_number = $data['visa_booking_ref_number'];
                                                        }
                           
                                                        if ($data['service'] == 'cruise') {
                                                            $prefix_booking_ref_number = $data['cruise_booking_ref_number'];
                                                        }
                           
                                                        if ($data['service'] == 'car') {
                                                            $prefix_booking_ref_number = $data['car_booking_ref_number'];
                                                        }
                           		?>
                        <tr>
                     
                                                   <td>

                        <?php
                        if ($data['action_type'] == 'booking' || $data['action_type'] == 'refund' ) { ?>
                           <a href="<?php echo service_log_link($data['service'], $prefix_booking_ref_number) ?>"> <?php echo $prefix_booking_ref_number; ?></a>
                        <?php } else {
                           if ($data['booking_ref_no']){ ?>
                              <a href="<?php echo service_log_link($data['service'], $prefix_booking_ref_number) ?>"> <?php echo $prefix_booking_ref_number; ?></a>
                           <?php }else {
                              echo '-';
                           }
                        } ?>
                        </td>
                          <!--  <td><?php echo $data['acc_ref_number']; ?></td> -->
                           <td>
                              <b>
                              <?php
                                 if ($data['action_type'] == 'booking') {
                                 	if ($data['service_log']) {
                                 		$service_log = json_decode($data['service_log'], true);
                                 		echo service_log($data['service'], $data['action_type'], $service_log);
                                 	} else {
                                 		echo ucfirst($data['service']) . ' ' . ucfirst($data['action_type']);
                                 	}
                                 } else {
                                 	echo ucfirst($data['action_type']);
                                 }
                                 ?>
                              </b><br/>
							  <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $data['remark'];?>"><?php echo limitTextChars($data['remark'],50, true, true); ?> </span>

                           </td>
                           <td>
                              <?php echo $data['debit']; ?>
                           </td>
                           <td><?php echo $data['balance']; ?></td>
                                                      <td>

                           <?php
                           $transaction_id = '';
                           if (isset($data['transaction_id'])) {
                              $transaction_id = '-' . $data['transaction_id'];
                           }

                           echo ucfirst($data['action_type'])."-";echo  $data['payment_mode'] != "" ? "<b></b> " . $data['payment_mode'] . $transaction_id . "<br/>" : "Wallet"; ?></td>

                       
                           <td>
                              <?php
                                 echo date_created_format($data['created']);
                                 ?>
                           </td>
                        </tr>
                        <?php
                           }
                           } else {
                           echo "<tr> <td colspan='11' class='text_center'><b>No Account Logs Found</b></td></tr>";
                           } ?>
                     </tbody>
                  </table>
					</div>
					<div class="row pagiantion_row align-items-center">
							<div class="col-md-6">
								<p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
														   of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
							</div>
							<div class="col-md-6">
								<?php if ($pager) : ?>
									<?= $pager->links() ?><?php endif ?>
							</div>
						</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>


<div id="view_agent" class="modal fade" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content" data-modal-view="view_modal_data"></div>
	</div>
</div>