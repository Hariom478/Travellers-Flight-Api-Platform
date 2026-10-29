<div class="content ">
<div class="page-content">
   <div class="table_title">
      <div class="sale_bar">
         <div class="row align-items-center">
            <div class="col-md-4 mb-3 mb-lg-0">
               <h5 class="m0"> Flight Cancelled Booking List</h5>
            </div>
            <div class="col-md-8 text-mdright">
            </div>
         </div>
      </div>
      <div class="page-content-area">
         <div class="card-body">
            <div class="row align-items-center">
               <!----------Start Search Bar ----------------->
               <form action="<?php echo site_url('flight/cancelled-bookings'); ?>" method="GET"
                  class="tts-dis-content" name="markup-search" onsubmit="return searchvalidateForm()">
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label> Search by Company ID / Company Name </label>
                        <input type="text" class="form-control" name="search-text"
                           value="<?php if (isset($search_bar_data['search-text'])) {
                              echo trim($search_bar_data['search-text']);
                              
                              } ?>" tts-get-web-partner-info="true"
                           tts-error-msg="Please enter search type"
                           placeholder="Search by Company ID / Company Name">
                        <input type="hidden" name="tts_web_partner_info" tts-web-partner-info-id="true"
                           value="<?php if (isset($search_bar_data['tts_web_partner_info'])) {
                              echo trim($search_bar_data['tts_web_partner_info']);
                              
                              } ?>">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>Select key to search by </label>
                        <select name="key" class="form-control"
                           onchange="tts_searchkey(this,'markup-search')"
                           tts-error-msg="Please select search key">
                           <option value="">Please select</option>
                           <option value="booking_ref_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_ref_number') {
                              echo "selected";
                              
                              } ?>>Booking Ref Number
                           </option>
                           <option value="first_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'first_name') {
                              echo "selected";
                              
                              } ?>>First Name
                           </option>
                           <option value="last_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'last_name') {
                              echo "selected";
                              
                              } ?>>Last Name
                           </option>
                           <option value="ticket_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'ticket_number') {
                              echo "selected";
                              
                              } ?>>Ticket No
                           </option>
                           <option value="pnr" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'pnr') {
                              echo "selected";
                              
                              } ?>>PNR
                           </option>
                           <option value="payment_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'payment_status') {
                              echo "selected";
                              
                              } ?>>Payment Status
                           </option>
                           <option value="journey_type" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'journey_type') {
                              echo "selected";
                              
                              } ?>>Journey Type
                           </option>
                        </select>
                     </div>
                     <input type="hidden" name="key-text"
                        value="<?php if (isset($search_bar_data['key-text'])) {
                           echo trim($search_bar_data['key-text']);
                           
                           } ?>">
                  </div>
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != '' && $search_bar_data['key'] != 'date-range') {
                           echo $search_bar_data['key-text'] . "";
                           
                           } else {
                           
                           echo "Value";
                           
                           } ?> </label>
                        <input type="text" name="value" placeholder="Value"
                           value="<?php if (isset($search_bar_data['value'])) {
                              echo $search_bar_data['value'];
                              
                              } ?>"
                           class="form-control" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                              echo "disabled";
                              
                              } ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                              } else {
                              
                                  /* echo 'tts-validatation="Required"'; */
                              
                              } ?> tts-error-msg="Please enter value"/>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label>From Date</label><input type="text" data-searchbar-from="true"
                           name="from_date"
                           value="<?php if (isset($search_bar_data['from_date'])) {
                              echo $search_bar_data['from_date'];
                              
                              } else {
                              
                              echo date('d M Y');
                              
                              } ?>" placeholder="Select From Date"
                           class="form-control"
                           tts-error-msg="Please select from date" readonly/>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date"
                           value="<?php if (isset($search_bar_data['to_date'])) {
                              echo $search_bar_data['to_date'];
                              
                              } else {
                              
                              echo date('d M Y');
                              
                              } ?>" placeholder="Select To Date" class="form-control"
                           tts-error-msg="Please select to date" readonly/>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label></label>
                        <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa fa-search"></i></button>
                     </div>
                  </div>
                  <? if (isset($search_bar_data['key'])) : ?>
                  <div class="col-md-3 mb-3">
                     <div class="search-reset-btn">
                        <a href="<?php echo site_url('flight/cancelled-bookings'); ?>">Reset Search</a>
                     </div>
                  </div>
                  <? endif ?>
               </form>
            </div>
            <!----------End Search Bar ----------------->
            <div class="table-responsive">
               <table class="table table-bordered table-hover">
                  <thead class="table-active">
                     <tr>
                        <th>Ref. No.</th>
                        <th>Company Name</th>
                        <th>Airline</th>
                        <th>Journey Type</th>
                        <th>Sector</th>
                        <th>PNR</th>
                        <th>Supplier</th>
                        <th>Fare</th>
                        <th>Pay Status</th>
                        <th>Book Status</th>
                        <th>Type</th>
                        <th>Assign User</th>
                        <th>Created</th>
                        <th>Summary</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                        if (!empty($list) && is_array($list)) {
                        
                            foreach ($list as $data) {
                        
                              $class = getStatusClass($data['booking_status']);
                              $payment_class = getStatusClass($data['payment_status']);
                        
                                ?>
                     <tr>
                        <td>
                           <a href="<?php echo site_url('/flight/details/') . $data['booking_ref_number']; ?>"
                              target="<?php echo target ?>"><?php echo $data['booking_ref_number']; ?></a>
                        </td>
                        <td><?php echo ucfirst($data['company_name']); ?>
                        </td>
                        <td><?php echo $data['validating_airline_code']; ?></td>
                        <td><?php echo $data['journey_type']; ?></td>
                        <td><?php echo ucfirst($data['origin']) . "-" . ucfirst($data['destination']); ?></td>
                        <td><?php echo $data['pnr']; ?></td>
                        <td> <?php echo $data['api_supplier']; ?> </td>
                        <td> <i class="fa fa-inr" aria-hidden="true"></i> <?php echo custom_money_format(round($data['total_price'])); ?></td>
                        <td>
                           <span class="<?php echo $payment_class ?>"><?php echo ucfirst($data['payment_status']); ?></span>
                        </td>
                        <td class="text_center">
                           <span class="<?php echo $class ?>">
                           <?php echo ucfirst($data['booking_status']); ?>
                           </span>
                        </td>
                        <td>
                           <span><?php echo $data['is_manual'] == 1 ? "" : "Online"; ?></span>
                           <br/>
                           <?php if ($data['is_manual'] == 1 && $data['update_ticket_by'] != null) {
                              $updateByinfo = json_decode($data['update_ticket_by'], true);
                              
                              if (is_array($updateByinfo)) {
                              
                                  echo  "Manual <br/>".$updateByinfo['first_name'] . " " . $updateByinfo['last_name'] ;
                              
                              }
                              
                              ?>
                           <?php } ?>
                        </td>
                        <td>
                           <?php if ($data['assign_user'] != NULL && $data['assign_user'] != '' && $data['assign_user'] == super_admin_cookie_data()['super_admin_user_details']['id']) { ?>
                           <?php echo $data['assign_user_name']; ?><br/>
                           <?php } else if ($data['assign_user'] != NULL && $data['assign_user'] != '' && $data['assign_user'] != super_admin_cookie_data()['super_admin_user_details']['id']) { ?>
                           <?php echo $data['assign_user_name']; ?><br/>
                           <?php  if ($data['booking_status'] == "Failed" || $data['booking_status'] == "Processing") { ?>
                           <a class="lead_assignbtn re_aassign"
                              href="<?php echo site_url('/flight/assign-update-flight-ticket/') . $ticketData = dev_encode($data['booking_ref_number']); ?>"
                              > ReAssign</a>
                           <?php } ?>
                           <?php } else {
                              if ($data['booking_status'] == "Failed" || $data['booking_status'] == "Processing") { ?>
                           <a class="lead_assignbtn aassign"
                              href="<?php echo site_url('/flight/assign-update-flight-ticket/') . $ticketData = dev_encode($data['booking_ref_number']); ?>"
                              > Assign</a>
                           <?php }
                              } ?>
                        </td>
                        <td><?php echo date_created_format($data['created']); ?>  </td>
                        <td>
                           <a href="<?php echo site_url('/flight/confirmation/') . $ticketData = dev_encode(json_encode(array($data['id']))); ?>"
                              target="<?php echo target ?>"><i class="tts-icon eye"> View</i></a>
                           <br/>
                           <?php if (($data['assign_user'] != NULL && $data['assign_user'] != '' && $data['assign_user'] == super_admin_cookie_data()['super_admin_user_details']['id'] && $data['assign_user'] == super_admin_cookie_data()['super_admin_user_details']['id']) || super_admin_cookie_data()['super_admin_user_details']['primary_user'] == 1) { ?>
                           <a href="<?php echo site_url('/flight-ticket-upload/get-update-flight-ticket-info/') . $ticketData = $data['booking_ref_number']; ?>"
                              target="<?php echo target ?>"><i class="tts-icon eye"> Edit <br/></i></a>
                           <?php } ?>
                        </td>
                     </tr>
                     <?php }
                        } else {
                        
                            echo "<tr> <td colspan='21' class='text_center'><b>No Booking Found</b></td></tr>";
                        
                        } ?>
                  </tbody>
               </table>
            </div>
            <div class="row pagiantion_row align-items-center">
               <div class="col-md-6 mb-3 mb-lg-0">
                  <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                     of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records
                     found 
                  </p>
               </div>
               <div class="col-md-6">
                  <?php if ($pager) : ?>
                  <?= $pager->links() ?>
                  <?php endif ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>