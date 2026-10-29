<section>
   <div class="container">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <h5 class="m-0">Cruise Markup</h5>
               </div>
               <div class="col-md-8 text-end">
                  <?php if (permission_access("Cruise", "add_cruise_markup")) { 
                     ?>
                  <button class="btn btn-outline-secondary btn-sm" view-data-modal="true" data-controller='cruise' data-href="<?php echo site_url('markup-discount/cruise-markup-view') ?>"><i class="fa-solid fa-add"></i> Add Cruise Markup
                  </button>
                  <?php } 
                     ?>
                  <?php if (permission_access("Cruise", "cruise_markup_status")) { 
                     ?>
                  <button class="btn btn-outline-secondary btn-sm" onclick="confirm_change_status('status_change')"><i class="fa-solid fa-exchange"></i> Change Status
                  </button>
                  <?php } 
                     ?>
                  <?php if (permission_access("Cruise", "delete_cruise_markup")) { 
                     ?>
                  <button class="btn btn-outline-secondary btn-sm danger disable_badge" tts-disable_badge onclick="confirm_delete('formmarkuplist')"><i class="fa-solid fa-trash"></i> Delete
                  </button>
                  <?php } 
                     ?>
               </div>
            </div>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <!----------Start Search Bar ----------------->
            <form action="<?php echo site_url('markup-discount/cruise-markup-list'); ?>" method="GET" name="markup-search" onsubmit="return searchvalidateForm()">
               <div class="row gy-3">
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>Select key to search by *</label>
                        <select name="key" class="form-select" onchange="tts_searchkey(this,'markup-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                           <option value="">Please select</option>
                           <option value="markup_type" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'markup_type') {
                              echo "selected";
                              } ?>>Markup Type</option>
                           <option value="cruise_line_id" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'cruise_line_id') {
                              echo "selected";
                              } ?>>Cruise Line</option>
                           <option value="cruise_ship_id" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'cruise_ship_id') {
                              echo "selected";
                              } ?>>Cruise Ship</option>
                           <option value="departure_port_id" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'departure_port_id') {
                              echo "selected";
                              } ?>>Departure Port</option>
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
                     <div class="form-group form-mb-20">
                        <label><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
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
                     <div class="form-group form-mb-20">
                        <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
                           echo $search_bar_data['from_date'];
                           } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                           echo $search_bar_data['to_date'];
                           } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly />
                     </div>
                  </div>
                  <div class="col-md-2 align-self-end">
                     <div class="form-group form-mb-20">
                        <button type="submit" class="btn btn-outline-primary badge_search">Search <i class="fa fa-search"></i></button>
                     </div>
                  </div>
                  <? if (isset($search_bar_data['key'])) : ?>
                  <div class="col-md-2 align-self-center">
                     <div class="search-reset-btn">
                        <a href="<?php echo site_url('markup-discount/cruise-markup-list'); ?>">Reset Search</a>
                     </div>
                  </div>
                  <? endif ?>
               </div>
            </form>
            <!----------End Search Bar ----------------->
            <?php
               $trash_uri = "markup-discount/remove-cruise-markup";
               ?>
            <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formmarkuplist">
               <div class="table-responsive mt-3">
                  <table class="table table-bordered table-hover">
                     <thead class="table-active">
                        <tr>
                           <?php if (permission_access("Cruise", "delete_cruise_markup") || permission_access("Cruise", "cruise_markup_status")) { 
                              ?>
                           <th><label><input type="checkbox" name="check_all" id="selectall" /></label>
                           </th>
                           <?php } 
                              ?>
                           <th>Cruise Line</th>
                           <th>Cruise Ship</th>
                           <th>Cruise Port</th>
                           <th>Value</th>
                           <th>Max Limit</th>
                           <th>Markup Type</th>
                           <th>Display Markup</th>
                           <th>Status</th>
                           <th>Created</th>
                           <th>Modified</th>
                           <?php  if (permission_access("Cruise", "edit_cruise_markup")) { 
                              ?>
                           <th>Action</th>
                           <?php } 
                              ?>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                           if (!empty($list) && is_array($list)) {
                              foreach ($list as $data) {
                           
                                 // Add This line Abhay Start 
                                 $class = ($data['status'] == 'active') ? 'active-status' : 'inactive-status';
                                 // Add This line Abhay End 
                           
                           ?>
                        <tr>
                           <?php if (permission_access("Cruise", "delete_cruise_markup") || permission_access("Cruise", "cruise_markup_status")) { 
                              ?>
                           <td>
                              <label><input type="checkbox" name="checklist[]" class="checkbox" value="<?php echo $data['id']; ?>" /></label>
                           </td>
                           <?php } 
                              ?>
                           <td>
                              <?php echo $data['cruise_line_name']; ?>
                           </td>
                           <td>
                              <?php echo $data['ship_name']; ?>
                           </td>
                           <td>
                              <?php echo $data['port_name']; ?>
                           </td>
                           <td><?php echo $data['value']; ?></td>
                           <td><?php echo $data['max_limit']; ?></td>
                           <td>
                              <?php echo $data['markup_type']; ?>
                           </td>
                           <td>
                              <?php echo ucwords(str_replace("_", " ", $data['display_markup'])); ?>
                           </td>
                           <td>
                              <span class="<?php echo $class ?>">
                              <?php echo ucfirst($data['status']); ?>
                              </span>
                           </td>
                           <td>
                              <?php echo date_created_format($data['created']); ?>
                           </td>
                           <td>
                              <?php
                                 if (isset($data['modified'])) {
                                    echo date_created_format($data['modified']);
                                 } else {
                                    echo '-';
                                 }
                                 ?>
                           </td>
                           <?php if (permission_access("Cruise", "edit_cruise_markup")) { 
                              ?>
                           <td>
                              <a href="javascript:void(0);" view-data-modal="true" data-controller='cruise' data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('/markup-discount/edit-cruise-markup-template/') . dev_encode($data['id']); ?>"><i class="fa-solid fa-edit"></i> Edit</a>
                           </td>
                           <?php } 
                              ?>
                        </tr>
                        <?php }
                           } else {
                              echo "<tr> <td colspan='14' class='text-center'><b>No Markup Found</b></td></tr>";
                           } ?>
                     </tbody>
                  </table>
               </div>
            </form>
            <div class="row">
               <div class="col-md-12 d-flex align-self-center justify-content-between">
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
</section>
<!-- status status change content -->
<div id="status_change" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-md" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Change Status</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('markup-discount/cruise-markup-status-change'); ?>" method="post" tts-form="true" name="form_change_status">
            <div class="modal-body">
               <div class="row gy-3">
                  <div class="col-md-12">
                     <div class="form-group form-mb-20">
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
               <button class="btn btn-primary" type="submit">Save</button>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- Show  status Modal content -->