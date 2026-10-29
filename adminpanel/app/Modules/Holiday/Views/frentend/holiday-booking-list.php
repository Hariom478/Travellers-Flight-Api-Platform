<div class="content ">
	<div class="page-content">
		<div class="table_title">
			<div class="sale_bar">
				<div class="row">
					<div class="col-md-4">
						<h5 class="m-0"> Holiday Booking List</h5>
					</div>
				</div>
			</div>
			<div class="page-content-area">
				<div class="card-body">
					
						<!----------Start Search Bar ----------------->
						<form class="row g-3 mb-3" action="<?php echo site_url('holiday/booking-list'); ?>" method="GET" class="tts-dis-content" name="holiday-search" onsubmit="return searchvalidateForm()">
							<div class="col-md-3">
								<div class="form-group">
									<label class="form-label">Select key to search by *</label>
									<select name="key" class="form-control" onchange="tts_searchkey(this,'holiday-search')" tts-validatation="Required" tts-error-msg="Please select search key">
										<option value="">Please select</option>

										<option value="booking_ref_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_ref_number') {
											echo "selected";
										} ?> >Ref No
										</option>


										<option value="first_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'first_name') {
											echo "selected";
										} ?> >First Name
										</option>
										<option value="last_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'last_name') {
											echo "selected";
										} ?> >Last Name
										</option>


										<option value="booking_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_status') {
											echo "selected";
										} ?> >Booking Status
										</option>
										<option value="payment_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'payment_status') {
											echo "selected";
										} ?> >Payment Status
										</option>

										<option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
											echo "selected";
										} ?>>Date Range
										</option>
									</select>
								</div>
								<input type="hidden" name="key-text" value="<?php if (isset($search_bar_data['key-text'])) {
									echo trim($search_bar_data['key-text']);
								} ?>">
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="form-label"><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
											echo $search_bar_data['key-text'] . " *";
										} else {
											echo "Value";
										} ?> </label>
									<input type="text" name="value" placeholder="Value" value="<?php if (isset($search_bar_data['value'])) {
										echo $search_bar_data['value'];
									} ?>" class="form-control" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
										echo "disabled";
									} ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
									} else {
										echo 'tts-validatation="Required"';
									} ?> tts-error-msg="Please enter value"/>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label class="form-label">From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
										echo $search_bar_data['from_date'];
									} ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly/>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label class="form-label">To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
										echo $search_bar_data['to_date'];
									} ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly/>
								</div>
							</div>
							<div class="col-md-2 align-self-end">
								<div class="form-group">
									
									<button type="submit" class="btn btn-primary">Search</button>
								</div>
							</div>
							<? if (isset($search_bar_data['key'])): ?>
								<div class="col-md-2">
									<div class="search-reset-btn">
										<a href="<?php echo site_url('holiday/booking-list'); ?>">Reset Search</a>
									</div>
								</div>
							<? endif ?>
						</form>
					


					<!----------End Search Bar ----------------->

					<div class="table-responsive">


						<table class="table table-bordered table-hover">
							<thead class="table-active">
							<tr>
								<th>Ref No.</th>
								<th>Name</th>
								<th>Package</th>
								<th>Duration</th>
								<th>Travel Date</th>
								<th>Booking Status</th>
								<th>Price</th>
								<th>Payment Status</th>
								<th>Created</th>


								<th>Action</th>

							</tr>
							</thead>
							<tbody>
							<?php
							if (!empty($list) && is_array($list)) {
								foreach ($list as $data) {
									 
									$class = getStatusClass($data['booking_status']);
									$payment_class = getStatusClass($data['payment_status']);

									$ticketData = dev_encode(json_encode(array("BookingId" => $data['id'], "BookingToken" => $data['tts_search_token'])));
									?>
									<tr>
										<td><a href="<?php echo site_url('holiday/holiday-booking-details/').$data['booking_ref_number']?>"><?php echo $data['booking_ref_number']; ?></a> </td>
										<td>
											<?php
											$name = ucfirst($data['title']) . ' ' . ucfirst($data['first_name']) . ' ' . ucfirst($data['last_name']);
											echo $name;
											?>
										</td>

										<td><?php echo $data['package_name']; ?></td>
										<td><?php echo $data['day_nights']; ?></td>
										<td><?php echo $data['travel_date']; ?></td>


										<td>
											<span class="<?php echo $class ?>">
												<?php echo ucfirst($data['booking_status']); ?>
											</span>
										</td>


										<td><?php echo $data['total_price']; ?></td>
										<td>
											<span class="<?php echo $payment_class ?>">
												<?php echo ucfirst($data['payment_status']); ?>
											</span>
										</td>
										<td>
											<?php echo date_created_format($data['created']); ?>
										</td>


										<td>
											<a href="<?php echo site_url('holiday/confirmation/' . $ticketData); ?>">View</a>
										</td>

									</tr>
								<?php }
							} else {
								echo "<tr> <td colspan='11' class='text_center'><b>No Booking Found</b></td></tr>";
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
			</div>
		</div>
	</div>

