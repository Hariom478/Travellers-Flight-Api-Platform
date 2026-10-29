<section>
   <div class="container">
      <div class="page-content">
   
         <div class="table_title">
   
            <div class="sale_bar">
   
               <div class="row align-items-center">
   
                  <div class="col-md-4">
   
                     <h6 class="m-0">Group Request List</h6>
   
                  </div>
   
                  <div class="col-md-8">
   
                     <div class="text-md-end">
   
                        <a href="<?= site_url('groupticket') ?>" class="badge badge-wt">Add Group Request</a>
   
                     </div>
   
                  </div>
   
               </div>
   
            </div>
   
         </div>
   
         <div class="page-content-area">
   
            <div class="card-body">
   
               <div class="row">
   
                  <!----------Start Search Bar ----------------->
   
                  <form action="<?php echo site_url('groupticket/request-list'); ?>" method="GET"
                     class="row align-items-center" name="group-ticket-search" onsubmit="return searchvalidateForm()">
   
                     <div class="col-md-3">
   
                        <div class="form-group">
   
                           <label class="form-label">Select key to search by *</label>
   
                           <select name="key" class="form-select" onchange="tts_searchkey(this,'group-ticket-search')"
                              tts-validatation="Required" tts-error-msg="Please select search key">
   
                              <option value="">Please select</option>
   
                              <option value="journey_type" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'journey_type') {
   
                                 echo "selected";
   
                              } ?>>Journey Type</option>
   
                              <option value="origin" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'origin') {
   
                                 echo "selected";
   
                              } ?>>Origin</option>
   
                              <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
   
                                 echo "selected";
   
                              } ?>>Date Range</option>
   
                           </select>
   
                        </div>
   
                        <input type="hidden" name="key-text" value="<?php if (isset($search_bar_data['key-text'])) {
   
                           echo trim($search_bar_data['key-text']);
   
                        } ?>">
   
                     </div>
   
                     <div class="col-md-2">
   
                        <div class="form-group">
   
                           <label class="form-label">
                              <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
   
                                 echo $search_bar_data['key-text'] . " *";
   
                              } else {
   
                                 echo "Value";
   
                              } ?>
                           </label>
   
                           <input type="text" name="value" placeholder="Value" value="<?php if (isset($search_bar_data['value'])) {
   
                              echo $search_bar_data['value'];
   
                           } ?>" class="form-control" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
   
                               echo "disabled";
   
                            } ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
   
                              } else {
   
                                 echo 'tts-validatation="Required"';
   
                              } ?>
                           tts-error-msg="Please enter value" />
   
                        </div>
   
                     </div>
   
                     <div class="col-md-2">
   
                        <div class="form-group">
   
                           <label class="form-label">From Date</label>
   
                           <input type="text" data-searchbar-from="true" name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
   
                              echo $search_bar_data['from_date'];
   
                           } ?>"
                              placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date"
                              readonly />
   
                        </div>
   
                     </div>
   
                     <div class="col-md-2">
   
                        <div class="form-group">
   
                           <label class="form-label">To Date</label><input type="text" data-searchbar-to="true"
                              name="to_date"
                              value="<?php if (isset($search_bar_data['to_date'])) {
   
                                 echo $search_bar_data['to_date'];
   
                              } ?>"
                              placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date"
                              readonly />
   
                        </div>
   
                     </div>
   
                     <div class="col-md-1 align-self-end">
   
                        <div class="form-group">
   
                           <button type="submit" class="badge badge-md badge-primary">Search</button>
   
                        </div>
   
                     </div>
   
                     <? if (isset($search_bar_data['key'])): ?>
   
                     <div class="col-md-2">
   
                        <div class="search-reset-btn">
   
                           <a href="<?php echo site_url('groupticket/request-list'); ?>">Reset Search</a>
   
                        </div>
   
                     </div>
   
                     <? endif ?>
   
                  </form>
   
               </div>
   
               <!----------End Search Bar ----------------->
   
               <div class="table-responsive mt-3">
   
                  <?php
   
                  $trash_uri = "groupticket/remove-groupticket";
   
                  ?>
   
                  <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formmarkuplist">
   
                     <table class="table table-bordered table-hover">
   
                        <thead class="table-active">
   
                           <tr>
   
                              <!--                                -->
                              <?php /*if (permission_access("Flight", "delete_flight_markup_b2c") || permission_access("Flight", "flight_markup_b2c_status")) { */?>
   
                              <!-- -->
                              <?php /*}*/?>
   
                              <th>Origin</th>
   
                              <th>Destination</th>
   
                              <th>Journey Type</th>
   
                              <th>Departure Date</th>
   
                              <th>Origin Preference Airline Code</th>
   
                              <th>Destination Preference Airline Code</th>
                              <th>Admin Remark</th>
   
                              <th>Status</th>
   
                              <!-- -->
                              <?php /*if (permission_access("Flight", "edit_flight_markup_b2c")) { */?>
   
                              <th>Action</th>
   
                              <?php /*}*/?>
   
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
   
                                    <!--                                        -->
                                    <?php /*if (permission_access("Flight", "delete_flight_markup_b2c") || permission_access("Flight", "flight_markup_b2c_status")) { */?>
   
                                    <?php /*}*/?>
   
                                    <td>
   
                                       <?php echo $data['origin']; ?>
   
                                    </td>
   
                                    <td>
   
                                       <?php echo $data['destination']; ?>
   
                                    </td>
   
                                    <td>
   
                                       <?php echo $data['journey_type']; ?>
   
                                    </td>
   
                                    <td>
   
                                       <?php echo $data['departure_date']; ?>
   
                                    </td>
   
                                    <td>
   
                                       <?php echo ucfirst($data['origin_preference_airline_code']); ?>
   
                                    </td>
   
                                    <td>
   
                                       <?php echo ucfirst($data['destination_preference_airline_code']); ?>
   
                                    </td>
                                    <td>
   
                                       <?php echo $data['admin_remark']; ?>
   
                                    </td>
   
                                    <td>
   
                                       <span class="<?php echo $class ?>">
   
                                          <?php echo ucfirst($data['status']); ?>
   
                                       </span>
   
                                    </td>
   
                                    <?php /*if (permission_access("Flight", "edit_flight_markup_b2c")) { */?>
   
                                    <td>
   
                                       <a href="javascript:void(0);" view-data-modal="false" data-controller='group_ticket'
                                          data-id="<?php echo dev_encode($data['id']); ?>"
                                          data-href="<?php echo site_url('/groupticket/group-ticket-view/') . dev_encode($data['id']); ?>"><i
                                             class="fa-solid fa-eye"></i></a>
   
                                    </td>
   
                                    <!-- -->
                                    <?php /*}*/?>
   
                                 </tr>
   
                              <?php }
   
                           } else {
   
                              echo "<tr> <td colspan='11' class='text_center'><b>No Group Request Found</b></td></tr>";
   
                           } ?>
   
                        </tbody>
   
                     </table>
   
                  </form>
   
               </div>
   
               <div class="row">
   
                  <div class="col-md-6">
   
                     <p class="pagiantion_text">Page
                        <?= $pager->getCurrentPage() ?>
   
                        of
                        <?= $pager->getPageCount() ?>, total
                        <?= $pager->getTotal() ?> records found
   
                     </p>
   
                  </div>
   
                  <div class="col-md-6">
   
                     <?php if ($pager): ?>
   
                        <?= $pager->links() ?>
   
                     <?php endif ?>
   
                  </div>
   
               </div>
   
            </div>
   
         </div>
   
      </div>
   </div>
</section>





<div id="view_group_ticket" class="modal fade" tabindex="-1">

   <div class="modal-dialog modal-dialog-centered modal-lg">

      <div class="modal-content" data-modal-view="view_modal_data"></div>

   </div>

</div>