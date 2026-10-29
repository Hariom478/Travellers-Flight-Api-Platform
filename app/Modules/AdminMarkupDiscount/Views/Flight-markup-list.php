<section class="dashboard-section d-flex align-items-center ">
   <div class="container">
      <div class="row">
         <div class="col-12">
            <div class="table_title">
               <div class="sale_bar">
                  <div class="row align-items-center">
                     <div class="col-md-4">
                        <h5 class="m-0">Flight Markup </h5>
                     </div>
                     <div class="col-md-8 text-end">
                        <?php if (permission_access("Flight", "add_flight_markup")) {
                        ?>
                           <button class="btn btn-secondary btn-sm" view-data-modal="false" data-controller='flight_markup' data-href="<?php echo site_url('markup-discount/flight-markup-view') ?>">
                              <i class="fa fa-plus"></i> Add Flight Markup
                           </button>
                        <?php } ?>
                        <?php if (permission_access("Flight", "flight_markup_status")) {  ?>
                           <button class="btn btn-primary btn-sm" onclick="confirm_change_status('status_change')"><i class="fa fa-exchange"></i> Change Status
                           </button>
                        <?php } ?>
                        <?php if (permission_access("Flight", "delete_flight_markup")) {  ?>
                           <button class="btn btn-danger btn-sm danger disable_badge" tts-disable_badge onclick="confirm_delete('formmarkuplist')"><i class="fa fa-trash"></i> Delete
                           </button>
                        <?php } ?>
                     </div>
                  </div>
               </div>
            </div>
            <div class="card">
               <div class="card-body">
                  <div class="row">
                     <!----------Start Search Bar ----------------->
                     <form action="<?php echo site_url('markup-discount/flight-markup'); ?>" method="GET" class="row align-items-center" name="markup-search" onsubmit="return searchvalidateForm()">
                        <div class="col-md-3">
                           <div class="form-group">
                              <label class="form-label">Select key to search by *</label>
                              <select name="key" class="form-select" onchange="tts_searchkey(this,'markup-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                                 <option value="">Please select</option>
                                 <option value="airline_code" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'airline_code') {
                                                                  echo "selected";
                                                               } ?>>Airline Code</option>
                                 <option value="markup_type" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'markup_type') {
                                                                  echo "selected";
                                                               } ?>>Markup Type</option>
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
                              <label class="form-label">From Date</label>
                              <input type="text" data-searchbar-from="true" name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
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
                              <button type="submit" class="btn btn-primary">Search <i class="fa fa-search"></i></button>
                           </div>
                        </div>
                        <? if (isset($search_bar_data['key'])) : ?>
                           <div class="col-md-2">
                              <div class="search-reset-btn">
                                 <a href="<?php echo site_url('markup-discount/flight-markup'); ?>">Reset Search</a>
                              </div>
                           </div>
                        <? endif ?>
                     </form>
                  </div>
                  <!----------End Search Bar ----------------->
                  <div class="table-responsive mt-3">
                     <?php
                     $trash_uri = "markup-discount/remove-markup";
                     ?>
                     <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formmarkuplist">
                        <table class="table table-bordered table-hover">
                           <thead class="table-active">
                              <tr>
                                 <?php if (permission_access("Flight", "delete_flight_markup") || permission_access("Flight", "flight_markup_status")) { ?>
                                    <th><label><input type="checkbox" name="check_all" id="selectall" /></label>
                                    </th>
                                 <?php } ?>
                                 <th>Airline Code</th>
                                 <th>Flight Type</th>
                                 <th>Markup Type</th>
                                 <th>Display Markup</th>
                                 <th>Amount</th>
                                 <th>Status</th>
                                 <?php if (permission_access("Flight", "edit_flight_markup")) { ?>
                                    <th>Action</th>
                                 <?php } ?>
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
                                       <?php if (permission_access("Flight", "delete_flight_markup") || permission_access("Flight", "flight_markup_status")) { ?>
                                          <td>
                                             <label><input type="checkbox" name="checklist[]" class="checkbox" value="<?php echo $data['id']; ?>" /></label>
                                          </td>
                                       <?php } ?>
                                       <td>
                                          <?php echo $data['airline_code'] . ' ' . '(' . $data['airline_name'] . ')'; ?>
                                       </td>
                                       <td>
                                          <?php
                                          $is_domestic = explode(',', $data['is_domestic']);
                                          if (in_array(1, $is_domestic)) {
                                             echo 'Domestic';
                                          }
                                          if (in_array(0, $is_domestic)) {
                                             echo ',International';
                                          }

                                          ?>
                                       </td>
                                       <td>
                                          <?php echo ucfirst($data['markup_type']); ?>
                                       </td>
                                       <td>
                                          <?php echo ucfirst(str_replace('_', ' ', $data['display_markup'])); ?>
                                       </td>
                                       <td>
                                          <?php echo $data['value']; ?>
                                       </td>
                                       <td>
                                          <span class="<?php echo $class ?>">
                                             <?php echo ucfirst($data['status']); ?>
                                          </span>
                                       </td>
                                       <?php if (permission_access("Flight", "edit_flight_markup")) { ?>
                                          <td>
                                             <a href="javascript:void(0);" view-data-modal="false" data-controller='flight_markup' data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('/markup-discount/edit-markup-template/') . dev_encode($data['id']); ?>"><i class="fa fa-edit "></i></a>
                                          </td>
                                       <?php } ?>
                                    </tr>
                              <?php }
                              } else {
                                 echo "<tr> <td colspan='11' class='text_center'><b>No Flight Markup Found</b></td></tr>";
                              } ?>
                           </tbody>
                        </table>
                     </form>
                  </div>
                  <div class="row">
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
   </div>
</section>
<!-- status status change content -->
<div id="status_change" class="modal fade" tabindex="-1">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <h5>Change Status</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('markup-discount/markup-status-change'); ?>" method="post" tts-form="true" name="form_change_status">
            <div class="modal-body">
               <div class="row">
                  <div class="tts-col-12">
                     <div class="form-group">
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
               <button class="badge badge-md badge-primary" type="submit" value="Save">Save</button>
            </div>
         </form>
      </div>
   </div>
</div>
<div id="view_flight_markup" class="modal fade" tabindex="-1">
   <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content" data-modal-view="view_modal_data"></div>
   </div>
</div>