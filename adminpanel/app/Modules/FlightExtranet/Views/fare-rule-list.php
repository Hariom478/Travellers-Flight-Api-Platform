<style>
   .inc_exc-tr {
   box-shadow: 1px 4px 9px -2px #dcdcdc;
   background: #fafafa;
   }
   .inc_exc {
   font-family: sans-serif;
   font-size: 13px;
   }
   .inc_exc .font_w-5 {
   font-weight: 500;
   color: #004684;
   font-size: 12px;
   }
   .inc_exc .inc_exc-content {
   font-size: 12px;
   margin-right: 20px;
   color: #6f6f6f;
   }
   .modal-body table, td, th {
   border-bottom: 1px solid #ddd;
   text-align: left;
   padding: 0.2rem 0.5rem;
   }
   /*    .table__body-list-font td {
   border-bottom: 1px solid #e5e5e5;
   vertical-align: middle !important;
   word-break: break-word;
   max-width: 250px;
   min-width: 100px;
   }*/
</style>
<div class="content">
   <div class="page-content">
      <div class="sale_bar">
         <div class="row align-items-center">
            <div class="col-md-4">
               <h5 class="m0"> Manage Fare Rule</h5>
            </div>
            <div class="col-md-8 text-end">
               <?php /*if (permission_access("Holiday", "add_agent")) { */ ?>
               <a href="<?php echo site_url("private-fare/fare-rule"); ?>"
                  class="badge badge-wt"><i class="fa-solid fa-add"></i> Add Fare Rule</a>
               <button class="badge badge-wt" onclick="confirm_change_status('status_change')">
               <i class="fa-solid fa-exchange"></i> Change Status
               </button>
               <?php /* }  */?>
               <?php /* } */ ?>
               <?php /*if (permission_access("Holiday", "delete_agent")) { */ ?>
               <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                  onclick="confirm_delete('remove-fare-rule')"><i class="fa-solid fa-trash"></i> Delete
               </button>
               <?php /* } */ ?>
            </div>
         </div>
      </div>
      <div class="page-content-area">
         <div class="card-body">
            <div class="row">
            <form action="<?php echo site_url('private-fare/fare-rule-list'); ?>" method="GET"
               class="col-md-12"
               name="web-partner-search" onsubmit="return searchvalidateForm()">
               <div class="row ">
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label></label>
                        <select name="key" class="form-control "
                           onchange="tts_searchkey(this,'web-partner-search')"
                           tts-validatation="Required"
                           tts-error-msg="Please select search key">
                           <option value="">Select key to search by *</option>
                           <option value="airline_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'airline_name') {
                              echo "selected";
                              
                              } ?>>Airline Name
                           </option>
                           <option value="airline_code" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'airline_code') {
                              echo "selected";
                              
                              } ?>>Airline Code
                           </option>
                           <option value="air_type" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'air_type') {
                              echo "selected";
                              
                              } ?>>Air Type
                           </option>
                           <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                              echo "selected";
                              
                              } ?>>Date Range
                           </option>
                        </select>
                        <input type="hidden" name="key-text"
                           value="<?php if (isset($search_bar_data['key-text'])) {
                              echo trim($search_bar_data['key-text']);
                              
                              } ?>">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label for="floatingDynamicId"><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
                           echo $search_bar_data['key-text'] . " *";
                           
                           } else {
                           
                           echo "Value";
                           
                           } ?></label>
                        <input type="text" class="form-control "
                           id="floatingDynamicId" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                              echo "disabled";
                              
                              } ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                              } else {
                              
                                  echo 'tts-validatation="Required"';
                              
                              } ?> tts-error-msg="Please enter value"
                           name="value" placeholder="Value"
                           value="<?php if (isset($search_bar_data['value'])) {
                              echo $search_bar_data['value'];
                              
                              } ?>">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label for="from-date">From Date</label>
                        <input type="text" class="form-control" id="from-date" data-searchbar-from="true"
                           name="from_date"
                           placeholder="Select From Date"
                           value="<?php if (isset($search_bar_data['from_date'])) {
                              echo $search_bar_data['from_date'];
                              
                              } ?>" tts-error-msg="Please select from date" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label for="to-date">From Date</label>
                        <input type="text" data-searchbar-to="true" name="to_date" id="to-date"
                           value="<?php if (isset($search_bar_data['to_date'])) {
                              echo $search_bar_data['to_date'];
                              
                              } ?>" placeholder="Select From Date" class="form-control"
                           tts-error-msg="Please select from date" readonly/>
                     </div>
                  </div>
                  <div class="col-md-2 align-self-end">
                     <div class="form-group">
                        <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa fa-search"></i></button>
                     
                     </div>
                  </div>
                  <div class="col-md-2 align-self-end">
                     <? if (isset($search_bar_data['key'])) : ?>
                  <a href="<?php echo site_url('private-fare/fare-rule-list'); ?>" class="btn sign_btn">Reset
                  Search</a>
                  <? endif ?>
                  </div>
            </form>
         </div>
         <div class="row mt-3">
            <?php $trash_uri = "private-fare/remove-fare-rule"; ?>
            <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="remove-fare-rule" class="col-md-12">
               <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                     <thead class="table-active">
                        <tr>
                           <?php /*if (permission_access("Holiday", "delete_agent") || permission_access("Holiday", "agent_status")) { */ ?>
                           <th><label class="m0"><input type="checkbox" name="check_all" id="selectall"/></label>
                           </th>
                           <!-- --><?php /*}*/ ?>
                           <th>Air Type</th>
                           <th>Airline</th>
                           <th>Refundable Type</th>
                           <th>Booking Class</th>
                           <th>Status</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody class="table__body-border">
                        <?php
                           if (!empty($list) && is_array($list)) {
                           
                           
                           
                               foreach ($list as $data) {
                           
                                   if ($data['status'] == "active") {
                           
                                       $enabled = 'Active';
                           
                                   } else {
                           
                                       $enabled = 'Inactive';
                           
                                   }
                           
                                   ?>
                        <tr class="table__row__container banklist__propper-header-class">
                           <!-- --><?php /*if (permission_access("Holiday", "delete_agent") || permission_access("Holiday", "agent_status")) { */ ?>
                           <td>
                              <label><input type="checkbox" name="checklist[]" class="checkbox"
                                 value="<?php echo $data['id']; ?>"/></label>
                           </td>
                           <?php /*}*/ ?>
                           <td>
                              <div class="generic-td ">
                                 <?php echo $data['air_type']; ?>
                              </div>
                           </td>
                           <td>
                              <div class="generic-td ">
                                 <?php echo $data['airline_name'] . '-' . $data['airline_code']; ?>
                              </div>
                           </td>
                           <td>
                              <div class="generic-td ">
                                 <?php echo $data['refundable_type']; ?>
                              </div>
                           </td>
                           <td class="generic-td ">
                              <?php echo $data['booking_class']; ?>
                           </td>
                           <td>
                              <div>
                                 <?php echo $enabled; ?>
                              </div>
                           </td>
                           <td class="generic-td ">
                              <a href="<?php echo site_url('/private-fare/edit-fare-rule/') . dev_encode($data['id']); ?>"
                                 data-controller='FlightExtranet'> <i class="fas fa-edit"></i> Edit</a>
                           </td>
                        </tr>
                        <?php }
                           } ?>
                     </tbody>
                  </table>
               </div>
            </form>
         </div>
         <div class="row pagiantion_row align-items-center">
            <div class="col-md-6 mb-3 mb-lg-0">
               <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                  of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found 
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

<div id="status_change" class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Status</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <form action="<?php echo site_url('private-fare/fare-rule-status-change'); ?>" method="post" tts-form="true" name="form_change_status">
            <div class="modal-body">
               <div class="row">
                  <div class="col-md-12">
                     <div class="form-group form-mb-20">
                        <select class="form-control" name="status">
                           <option value="" selected="selected">Status</option>
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