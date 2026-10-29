<section class="dashboard-section d-flex align-items-center ">
   <div class="container">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row">
               <div class="col-md-4">
                  <h5 class="m-0"> Flight Booking List</h5>
               </div>
            </div>
         </div>
         <div class="card">
            <div class="card-body">

                  <!----------Start Search Bar ----------------->
                  <form action="<?php echo site_url('flight/bookings-search'); ?>" method="GET" class="row" name="markup-search" onsubmit="return searchvalidateForm()">
                     <div class="col-md-3">
                        <div class="form-group">
                           <label class="form-label">Select key to search by *</label>
                           <select name="key" class="form-select" onchange="tts_searchkey(this,'markup-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                              <option value="">Please select</option>
                              <option value="booking_ref_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_ref_number') {
                                 echo "selected";
                                 } ?>>Booking Ref Number</option>
                              <option value="first_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'first_name') {
                                 echo "selected";
                                 } ?>>First Name</option>
                              <option value="last_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'last_name') {
                                 echo "selected";
                                 } ?>>Last Name</option>
                              <option value="ticket_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'ticket_number') {
                                 echo "selected";
                                 } ?>>Ticket No</option>
                              <option value="pnr" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'pnr') {
                                 echo "selected";
                                 } ?>>PNR</option>
                              <option value="booking_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_status') {
                                 echo "selected";
                                 } ?>>Booking Status</option>
                              <option value="payment_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'payment_status') {
                                 echo "selected";
                                 } ?>>Payment Status</option>
                              <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                 echo "selected";
                                 } ?>>Date Range</option>
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
                              } ?> tts-error-msg="Please enter value" />
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="form-group">
                           <label class="form-label">From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
                              echo $search_bar_data['from_date'];
                              } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="form-group">
                           <label class="form-label">To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                              echo $search_bar_data['to_date'];
                              } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly />
                        </div>
                     </div>
                     <div class="col-md-2 align-self-end">
                        <div class="form-group">
                           <button type="submit" class="btn btn-primary btn_search">Search <i class="fa fa-search"></i></button>
                        </div>
                     </div>
                     <? if (isset($search_bar_data['key'])) : ?>
                     <div class="col-md-2">
                        <div class="search-reset-btn">
                           <a href="<?php echo site_url('flight/bookings'); ?>">Reset Search</a>
                        </div>
                     </div>
                     <? endif ?>
                  </form>
      
               <!----------End Search Bar ----------------->
               <div class="table-responsive mt-3">
                  <table class="table table-bordered table-hover">
                     <thead class="table-active">
                        <tr>
                           <th>Ref. No.</th>
                           <th>Created On</th>
                           <th>Journey Type</th>
                           <th>Pax Name</th>
                           <th>Travel Sector</th>
                           <th>Travel Date</th>
                           <th>Fly Type</th>
                           <th>Airline</th>
                           <th>Airline Pnr</th>
                           <th>Fare type</th>
                           <th>Invoice Fare</th>
                           <th>Txn Status</th>
                           <th>Booking Status</th>
                           <th>Last Ticket Date </th>
                           <th>Booked By </th>
                           <th>Summary</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                           if (!empty($list) && is_array($list)) {
                              foreach ($list as $data) {
                                 if ($data['booking_status'] == 'Confirmed') {
                                    $class = 'tts-text-success';
                                 } else {
                                    $class = 'tts-text-danger';
                                 }
                           
                                 if ($data['payment_status'] == 'Successful') {
                                    $payment_class = 'tts-text-success';
                                 } else {
                                    $payment_class   = 'tts-text-danger';
                                 }
                           ?>
                        <tr>
                           <td> <a href="<?php echo site_url('/flight/details/') . $data['booking_ref_number']; ?>" target="_blank"><?php echo $data['booking_ref_number']; ?></a></td>
                           <td>
                              <?php echo date_created_format($data['created']); ?>
                           </td>
                           <td><?php echo ucfirst($data['journey_type']); ?></td>
                           <td><?php echo ucfirst($data['lead_passenger_name']); ?></td>
                           <td><?php echo ucfirst($data['origin']) . "-" . ucfirst($data['destination']); ?></td>
                           <td><?php echo display_custom_date_format($data['departure_date']); ?></td>
                           <td><?php echo $data['is_domestic'] == 1 ? "Domestic" : "International"; ?></td>
                           <td> <?php echo $data['validating_airline_code']; ?></td>
                           <td><?php echo $data['pnr']; ?></td>
                           <td><span class="<?php echo $data['is_refundable'] == 1 ? "tts-text-success" : "tts-text-danger"; ?>"><?php echo $data['is_refundable'] == 1 ? "Refundable" : "Non-Refundable"; ?></span></td>
                           <td> ₹ <?php echo $data['total_price']; ?></td>
                           <td> <span class="<?php echo $payment_class ?>"><?php echo ucfirst($data['payment_status']); ?></span></td>
                           <td>
                              <span class="<?php echo $class ?>">
                              <?php echo ucfirst($data['booking_status']); ?>
                              </span>
                           </td>
                           <td>
                              <?php echo $data['last_ticket_date'] != "" ? display_custom_date_format($data['last_ticket_date'], true) : ""; ?>
                           </td>
                           <td>
                              <?php echo $data['staff_name']; ?>
                           </td>
                           <td>
                              <a href="<?php echo site_url('/flight/confirmation/') .   $ticketData  =  dev_encode(json_encode(array($data['id']))); ?>" target="_blank"><i class="fa fa-eye"> </i> View</a>
                           </td>
                        </tr>
                        <?php }
                           } else {
                              echo "<tr> <td colspan='18' class='text_center'><b>No Booking Found</b></td></tr>";
                           } ?>
                     </tbody>
                  </table>
               </div>
               <div class="row mt-3">
                  <div class="col-md-12 d-flex align-items-center justify-content-between">
                     <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                        of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found
                     </p>
                     <span>
                        <?php if ($pager) : ?>
                     <?= $pager->links() ?>
                     <?php endif ?>
                     </span>
                  </div>
                  
               </div>
            </div>
         </div>
      </div>
   </div>
</section>