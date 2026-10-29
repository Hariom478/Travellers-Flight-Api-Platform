<div class="content ">
   <div class="page-content">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4 mb-3 mb-lg-0">
                  <h5 class="m0"> Private Fare Detail List</h5>
               </div>
               <div class="col-md-8 text-md-right">
                  <?php /*if (permission_access("Holiday", "add_agent")) { */?>
                  <button class="badge badge-wt" view-data-modal="true" data-controller="flightextranet" data-href="<?php echo site_url("private-fare/add-private-fare-pnr-page/".dev_encode($privateFareId)); ?>"><i class="fa-solid fa-add"></i> Add PNR
                  </button>
                  <!-- <?php /*}*/?>
                     --><?php /*if (permission_access("Holiday", "agent_status")) { */?>
                  <button class="badge badge-wt" onclick="confirm_change_status('status_change')"><i class="fa-solid fa-exchange"></i> Change Status
                  </button>
                  <!-- <?php /*}*/?>
                     --><?php /*if (permission_access("Holiday", "delete_agent")) { */?>
                  <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                     onclick="confirm_delete('formagentlist')"><i class="fa-solid da-trash"></i> Delete
                  </button>
                  <?php /*}*/?>
               </div>
            </div>
         </div>
      </div>
      <div class="page-content-area">
         <div class="card-body">
            <div class="row">
               <div class="col-md-2">
                  <div class="vi_mod_dsc">
                     <span> Reference  Number</span>
                     <span class="primary"> <b><?php echo  $PrivateFareInfo['reference_number']; ?></b> </span>
                  </div>
               </div>
               <div class="col-md-2">
                  <div class="vi_mod_dsc">
                     <span>Origin-Destination</span>
                     <span class="primary"> <b><?php echo  ucfirst($PrivateFareInfo['origin_airport_code'])."-". ucfirst($PrivateFareInfo['destination_airport_code']); ?> </b> </span>
                  </div>
               </div>
               <div class="col-md-2">
                  <div class="vi_mod_dsc">
                     <span>Cabin Class</span>
                     <span class="primary"> <b><?php echo  ucfirst($PrivateFareInfo['business_type']); ?></b> </span>
                  </div>
               </div>
               <div class="col-md-2">
                  <div class="vi_mod_dsc">
                     <span>Journey Type</span>
                     <span class="primary"> <b><?php echo  ucfirst($PrivateFareInfo['journey_type']); ?> </b> </span>
                  </div>
               </div>
               <div class="col-md-2">
                  <div class="vi_mod_dsc">
                     <span>Trip Type</span>
                     <span class="primary"> <b><?php echo  ucfirst($PrivateFareInfo['trip_type']); ?> </b> </span>
                  </div>
               </div>
            </div>
            <div class="row">
               <!----------Start Search Bar ----------------->
               <form action="<?php echo site_url('private-fare/private-fare-list'); ?>" method="GET" class="tts-dis-content" name="holiday-search" onsubmit="return searchvalidateForm()">
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>Select key to search by *</label>
                        <select name="key" class="form-control" onchange="tts_searchkey(this,'holiday-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                           <option value="">Please select</option>
                           <option value="holiday_name" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='holiday_name'){ echo "selected";} ?>>Package Name</option>
                           <option value="date-range" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range'){ echo "selected";} ?>>Date Range</option>
                        </select>
                     </div>
                     <input type="hidden" name="key-text" value="<?php if(isset($search_bar_data['key-text'])){ echo trim($search_bar_data['key-text']); } ?>">
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label><?php if(isset($search_bar_data['key']) && $search_bar_data['key']!='date-range') { echo $search_bar_data['key-text']. " *"; } else { echo "Value"; } ?> </label>
                        <input type="text" name="value" placeholder="Value"  value="<?php if(isset($search_bar_data['value'])){ echo $search_bar_data['value']; } ?>" class="form-control" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range') { echo "disabled"; } ?> <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range') {  } else { echo 'tts-validatation="Required"'; } ?>   tts-error-msg="Please enter value" />
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if(isset($search_bar_data['from_date'])){ echo $search_bar_data['from_date']; } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly/>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if(isset($search_bar_data['to_date'])){ echo $search_bar_data['to_date']; } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly/>
                     </div>
                  </div>
                  <div class="col-md-1">
                     <div class="form-group form-mb-20">
                        <label></label><br />
                        <button type="submit" class="badge badge-md badge-primary">Search</button>
                     </div>
                  </div>
                  <? if(isset($search_bar_data['key'])): ?>
                  <div class="col-md-1">
                     <div class="search-reset-btn">
                        <a href="<?php echo site_url('private-fare/private-fare-list');?>">Reset Search</a>
                     </div>
                  </div>
                  <? endif ?>
               </form>
            </div>
            <!----------End Search Bar ----------------->
            
               <?php $trash_uri = "private-fare/remove-private-fare"; ?>
               <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formagentlist">
                  <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                     <thead class="table-active">
                        <tr>
                           <?php /*if (permission_access("Holiday", "delete_agent") || permission_access("Holiday", "agent_status")) { */?>
                           <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                           </th>
                           <!-- --><?php /*}*/?>
                           <th>Flight Date</th>
                           <th>PNR</th>
                           <th>Seat Info</th>
                           <th>Price Info</th>
                           <th>Status</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                           if (!empty($list) && is_array($list)) {
                           
                               foreach ($list as $data) {
                           
                           
                           
                                   if ($data['status'] == 'active') {
                           
                                       $class = 'active-status';
                           
                                   } else {
                           
                                       $class = 'inactive-status';
                           
                                   }
                           
                           
                           
                           
                           
                                   ?>
                        <tr>
                           <!-- --><?php /*if (permission_access("Holiday", "delete_agent") || permission_access("Holiday", "agent_status")) { */?>
                           <td>
                              <label><input type="checkbox" name="checklist[]" class="checkbox"
                                 value="<?php echo $data['id']; ?>"/></label>
                           </td>
                           <?php /*}*/?>
                           <td>
                              <a href  =  "<?php echo   site_url('private-fare/private-fare-option/'.dev_encode($data['id'])) ?>"><?php echo $data['reference_number']; ?></a>
                           </td>
                           <td>
                              <?php echo $data['origin_airport_code'].'-'.$data['destination_airport_code']; ?>
                           </td>
                           <td><?php echo $data['onward_stops']; ?></td>
                           <td><?php echo $data['return_stops']; ?></td>
                           <td>
                              <span class="<?php echo $class ?>">
                              <?php echo ucfirst($data['status']); ?>
                              </span>
                           </td>
                           <td>
                              <a href="<?php echo site_url('/private-fare/edit-private-fare-page/') . dev_encode($data['id']); ?>"  data-controller='FlightExtranet'><i
                                 class="tts-icon edit "></i></a>
                           </td>
                        </tr>
                        <?php }
                           } else {
                           
                               echo "<tr> <td colspan='11' class='text_center'><b>No Record Found</b></td></tr>";
                           
                           } ?>
                     </tbody>
                  </table>
               </div>
            </form>
            
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
</div>
<!-- status status change content -->
<div id="status_change" class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Change Status</h5>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>

         <form action="<?php echo site_url('private-fare/private-fare-status-change'); ?>" method="post" tts-form="true" name="form_change_status">
            <div class="modal-body">
               <div class="row">
                  <div class="col-md-12">
                     <div class="form-group form-mb-20">
                        <select class="form-control" name="status">
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
               <button class="btn btn-primary" type="submit" >Save</button>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- Show  status Modal content -->