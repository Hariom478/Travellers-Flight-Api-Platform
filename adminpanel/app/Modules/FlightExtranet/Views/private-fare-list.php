<div class="content">
   <div class="page-content">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <h5 class="m0"> Manage Private Fare</h5>
               </div>
               <div class="col-md-8 text-md-end">
               <?php if (permission_access("FlightExtranet", "add_private_fare")) { ?>
                  <a href="<?php echo site_url("private-fare/add-private-fare-page"); ?>" class="badge badge-wt"><i class="fa-solid fa-add"></i> Add Fare</a>
                  <?php } ?>
                  <?php if (permission_access("FlightExtranet", "private_fare_status")) { ?>
                  <button class="badge badge-wt" onclick="confirm_change_status('status_change')"><i class="fa-solid fa-exchange"></i> Change Status </button>
                  <?php } ?>
                  <?php if (permission_access("FlightExtranet", "delete_private_fare")) { ?>
                  <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge onclick="confirm_delete('remove-private-fare')"><i class="fa-solid fa-trash"></i> Delete</button>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <div class="row">
               <form action="<?php echo site_url('private-fare/private-fare-list'); ?>" method="GET" class="col-md-12" name="web-partner-search" onsubmit="return searchvalidateForm()">
                  <div class="row">
                     <div class="col-md-3">
                        <div class="form-group form-mb-20">
                           <label>Select key to search by *</label>
                           <select name="key" class="form-select " onchange="tts_searchkey(this,'web-partner-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                              <option value="">Select key to search by *</option>
                              <option value="inventory_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'inventory_name') { echo "selected";  } ?>>Inventory Name </option>
                              <option value="status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'status') {  echo "selected";  } ?>>Status  </option>
                              <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {  echo "selected"; } ?>>Date Range  </option>
                           </select>
                           <input type="hidden" name="key-text" value="<?php if (isset($search_bar_data['key-text'])) { echo trim($search_bar_data['key-text']); } ?>">
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="form-group form-mb-20">
                           <label for="floatingDynamicId"><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {  echo $search_bar_data['key-text'] . " *";  } else { echo "Value"; } ?></label>
                           <input type="text" class="form-control " id="floatingDynamicId" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {  echo "disabled";  } ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {  } else { echo 'tts-validatation="Required"';  } ?> tts-error-msg="Please enter value" name="value" placeholder="Value" value="<?php if (isset($search_bar_data['value'])) {  echo $search_bar_data['value'];  } ?>">
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="form-group form-mb-20">
                           <label for="from-date">From Date</label>
                           <input type="text" class="form-control" id="from-date" data-searchbar-from="true" name="from_date" placeholder="Select From Date" value="<?php if (isset($search_bar_data['from_date'])) {  echo $search_bar_data['from_date'];  } ?>" tts-error-msg="Please select from date" readonly>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="form-group form-mb-20">
                           <label for="to-date">From Date</label>
                           <input type="text" data-searchbar-to="true" name="to_date" id="to-date" value="<?php if (isset($search_bar_data['to_date'])) {  echo $search_bar_data['to_date'];  } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                        </div>
                     </div>
                     <div class="col-md-2 align-self-end">
                        <div class="form-group">
                           <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa fa-search"></i></button>
                        </div>
                     </div>
                     <div class="col-md-2 align-self-end">
                        <? if (isset($search_bar_data['key'])) : ?>
                        <a href="<?php echo site_url('private-fare/private-fare-list'); ?>">Reset
                           Search</a>
                     <? endif ?>
                     </div>
                  </div>
               </form>
               <div class="col-md-12 mt-3">
                  <?php $trash_uri = "private-fare/remove-private-fare"; ?>
                  <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="remove-private-fare">
                     <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                           <thead class="table-active">
                              <tr>
                                 <?php if (permission_access("FlightExtranet", "delete_private_fare") || permission_access("FlightExtranet", "fare_rule_status")) { ?>
                                 <th><label class="m0"><input type="checkbox" name="check_all" id="selectall" /></label>
                                 </th>
                                 <?php } ?>
                                 <th>Inventory Name</th>
                        <th>Source</th>
                        <th>Source Terminal</th>
                        <th>Trip Type</th>
                        <th>Journey Type</th>
                        <th>Destination</th>
                        <th>Destination Terminal</th>
                        <th>Flight Number</th>
                        <th>Airline</th>
                        <th>Seat Allocation</th>
                                 <th>Status</th>
                                 <?php if (permission_access("FlightExtranet", "edit_private_fare")) { ?>
                                 <th>Action</th>
                                 <?php } ?>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              if (!empty($list) && is_array($list)) {
                                 foreach ($list as $data) {
                                    if ($data['status'] == 'active') {
                                       $class = 'text-success';
                                    } else {
                                       $class = 'text-danger';
                                    }
                                    $segment_detail = json_decode($data['onward_segment_detail'], true);
                                    $segment_detail_count = count($segment_detail);
                                    $segment_detail_first = reset($segment_detail);
                                    $origin_airport_code = isset($segment_detail_first[0]['origin_airport_code']) ? $segment_detail_first[0]['origin_airport_code'] : 'NA'; 
                                    $origin_terminal = (isset($segment_detail_first[0]['origin_terminal']))?$segment_detail_first[0]['origin_terminal']:'NA';
                                    $airline_code = (isset($segment_detail_first[0]['airline_code']))?$segment_detail_first[0]['airline_code']:'NA';
                                    $flight_number = (isset($segment_detail_first[0]['flight_number']))?$segment_detail_first[0]['flight_number']:'NA';
                                    $destination_airport_code = (isset($segment_detail_first[0]['destination_airport_code']))?$segment_detail_first[0]['destination_airport_code']:'NA';
                                    $destination_terminal = (isset($segment_detail_first[0]['destination_terminal']))?$segment_detail_first[0]['destination_terminal']:'NA';
                                    
                                    
                                    if ($segment_detail_count > 1) {
                                       $segment_detail_last = end($segment_detail[0]);
                                       $destination_airport_code = (isset($segment_detail_last['destination_airport_code']))?$segment_detail_last['destination_airport_code']:'NA';
                                       $destination_terminal = (isset($segment_detail_last['destination_terminal']))?$segment_detail_last['destination_terminal']:'NA';
                                    }
                              ?>
                                    <tr class="table__row__container banklist__propper-header-class">
                                       <?php if (permission_access("FlightExtranet", "delete_private_fare") || permission_access("FlightExtranet", "fare_rule_status")) {  ?>
                                       <td>
                                          <label class="m0"><input type="checkbox" name="checklist[]" class="checkbox" value="<?php echo $data['id']; ?>" /></label>
                                       </td>
                                       <?php } ?>
                                       <td>
                           <div class="generic-td ">
                              <?php echo $data['inventory_name']; ?>
                           </div>
                        </td>
                        <td>
                           <div class="generic-td ">
                              <?php echo $origin_airport_code; ?>
                           </div>
                        </td>
                      
                        <td>
                           <div class="generic-td ">
                              <?php echo $origin_terminal; ?>
                           </div>
                        </td>

                        <td>
                           <div class="generic-td ">
                              <?php echo $data['trip_type']; ?>
                           </div>
                        </td>
                        <td>
                           <div class="generic-td ">
                              <?php echo $data['journey_type']; ?>
                           </div>
                        </td>
                        <td class="generic-td ">
                           <?php echo $destination_airport_code; ?>
                        </td>
                        <td class="generic-td ">
                           <?php echo $destination_terminal; ?>
                        </td>
                        <td class="generic-td ">
                           <?php echo $flight_number; ?>
                        </td>
                        <td class="generic-td ">
                           <?php echo $airline_code; ?>
                        </td>
                        <td><a href="<?php echo site_url('/private-fare/seat-allocation/') . dev_encode($data['id']); ?>"><i class="fa fa-eye"></i></a></td>
                                       <td>
                                          <div class="<?php echo $class ?>">
                                             <?php echo $data['status']; ?>
                                          </div>
                                       </td>
                                       <?php if (permission_access("FlightExtranet", "edit_private_fare")) { ?>
                                       <td class="generic-td ">
                                          <a href="<?php echo site_url('/private-fare/edit-private-fare-page/') . dev_encode($data['id']); ?>" data-controller='FlightExtranet'><i class="fa-solid fa-edit"></i></a>
                                       </td>
                                       <?php } ?>
                                    </tr>
                              <?php }
                              } ?>
                           </tbody>
                        </table>
                     </div>
                  </form>
               </div>
               <div class="col-md-12">
                  <div class="row pagiantion_row align-items-center">
                     <div class="col-md-6 mb-3 mb-lg-0">
                        <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?> of <?= $pager->getPageCount() ?>,
                           total <?= $pager->getTotal() ?> records found </p>
                     </div>
                     <div class="col-md-6">
                        <?php if ($pager) : ?> <?= $pager->links() ?> <?php endif ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
 

<div id="status_change" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title fs-5">Change Status</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('private-fare/private-fare-status-change'); ?>" method="post" tts-form="true" name="form_change_status">
            <div class="modal-body">
               <div class="row">
                  <div class="col-12">
                     <div class="form-group form-mb-20">
                        <label class="form-label">Select Status</label>
                        <select class="form-select" name="status">
                           <option value="" selected="selected">Select Status</option>
                           <option value="active">Active</option>
                           <option value="inactive">Inactive</option>
                        </select>
                        <input type="hidden" name="checkedvalue">
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button class="btn btn-primary" type="submit" value="Save">Save</button>
            </div>
         </form>
      </div>
   </div>
</div>